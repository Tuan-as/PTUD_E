<?php
// FILE: store_pages/fetchdata/process_checkout.php
session_start();
require_once '../../db.php';
header('Content-Type: application/json');

// Bắt lỗi hệ thống để trả về JSON thay vì HTML lỗi
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    if (!isset($_SESSION['user_id'])) throw new Exception('Phiên đăng nhập hết hạn');

    $user_id = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);

    // --- LOGIC HỦY ĐƠN ---
    if (isset($input['action']) && $input['action'] == 'cancel_order') {
        $order_id = intval($input['order_id']);
        // Check quyền sở hữu (dùng MaNguoiDung)
        $chk = $conn->query("SELECT MaDonHang FROM DonHang WHERE MaDonHang=$order_id AND MaNguoiDung=$user_id");
        if ($chk->num_rows > 0) {
            $conn->query("UPDATE DonHang SET TrangThai='CANCELLED' WHERE MaDonHang=$order_id");
            // Hoàn kho
            $details = $conn->query("SELECT MaSKU, SoLuong FROM CTDonHang WHERE MaDonHang=$order_id");
            while ($d = $details->fetch_assoc()) {
                $sku = $d['MaSKU'];
                $qty = $d['SoLuong'];
                $conn->query("INSERT INTO LichSuThayDoiTonKho (MaSKU, LoaiThayDoi, SoLuong, NguoiThucHien, GhiChu) VALUES ($sku, 'Nhập kho', $qty, $user_id, 'Hủy đơn #$order_id')");
            }
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Không tìm thấy đơn hàng');
        }
        exit;
    }

    // --- LOGIC ĐẶT HÀNG ---
    $address_id = intval($input['address_id'] ?? 0);
    $items_buy = $input['items'] ?? [];
    $payment_method = $input['payment_method'] ?? 'COD';
    $shipping_method = $input['shipping_method'] ?? 'Nhanh';
    $shipping_fee = floatval($input['shipping_fee'] ?? 0);
    $voucher_id = !empty($input['voucher_id']) ? intval($input['voucher_id']) : "NULL";
    $order_note = isset($input['order_note']) ? $conn->real_escape_string($input['order_note']) : '';

    if (empty($items_buy) || !$address_id) throw new Exception('Dữ liệu đơn hàng không hợp lệ');

    $conn->begin_transaction();

    $subtotal = 0;
    foreach ($items_buy as $item) {
        $sku_id = intval($item['sku_id']);
        $qty = intval($item['qty']);
        
        // Check tồn kho
        $resSku = $conn->query("SELECT GiaGoc, GiaGiam, TonKho FROM SKU WHERE MaSKU = $sku_id FOR UPDATE");
        $skuData = $resSku->fetch_assoc();
        
        if ($skuData['TonKho'] < $qty) throw new Exception("Sản phẩm #$sku_id không đủ hàng");
        
        $price = $skuData['GiaGiam'] > 0 ? $skuData['GiaGiam'] : $skuData['GiaGoc'];
        $subtotal += $price * $qty;
    }

    // Tính Voucher
    $discount = 0;
    if ($voucher_id !== "NULL") {
        $resV = $conn->query("SELECT * FROM MaGiamGia WHERE MaGiamGia = $voucher_id AND TrangThai = 'ACTIVE' AND NgayHetHan >= NOW()");
        if ($resV->num_rows > 0) {
            $v = $resV->fetch_assoc();
            if ($subtotal >= $v['GiaTriDonHangToiThieu']) {
                if ($v['LoaiGiamGia'] == 'FIXED') $discount = $v['MucGiamGia'];
                else $discount = $subtotal * ($v['MucGiamGia'] / 100);
            }
        }
    }

    $final_total = $subtotal + $shipping_fee - $discount;
    if ($final_total < 0) $final_total = 0;

    // INSERT ĐƠN HÀNG (CÓ CỘT MaNguoiDung)
    // Nếu bảng DonHang chưa có GhiChu thì bỏ biến $order_note đi
    $sqlOrder = "INSERT INTO DonHang (MaNguoiDung, NgayDat, TongTien, MaGiamGia, MaDiaChiNhanHang, TrangThai, GhiChu) 
                 VALUES ($user_id, NOW(), $final_total, $voucher_id, $address_id, 'PENDING', '$order_note')";
    
    if (!$conn->query($sqlOrder)) throw new Exception("Lỗi SQL: " . $conn->error);
    $order_id = $conn->insert_id;

    // Chi tiết đơn & Trừ kho
    foreach ($items_buy as $item) {
        $sku_id = intval($item['sku_id']);
        $qty = intval($item['qty']);
        $resSku = $conn->query("SELECT GiaGoc, GiaGiam FROM SKU WHERE MaSKU=$sku_id");
        $skuData = $resSku->fetch_assoc();
        $price = $skuData['GiaGiam'] > 0 ? $skuData['GiaGiam'] : $skuData['GiaGoc'];

        $conn->query("INSERT INTO CTDonHang (MaDonHang, MaSKU, SoLuong, DonGia) VALUES ($order_id, $sku_id, $qty, $price)");
        $conn->query("INSERT INTO LichSuThayDoiTonKho (MaSKU, LoaiThayDoi, SoLuong, NguoiThucHien, GhiChu) VALUES ($sku_id, 'Xuất kho', $qty, $user_id, 'Đơn hàng #$order_id')");
        $conn->query("DELETE FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");
    }

    // Vận chuyển & Giao dịch
    $conn->query("INSERT INTO DonVanChuyen (MaDonHang, PhuongThucVanChuyen, TrangThaiVanChuyen) VALUES ($order_id, '$shipping_method', 'PENDING')");
    $conn->query("INSERT INTO GiaoDich (MaDonHang, PhuongThuc, LoaiGiaoDich, GiaTri, TrangThai) VALUES ($order_id, '$payment_method', 'PAYMENT', $final_total, 'PENDING')");

    // Update Voucher usage
    if ($voucher_id !== "NULL") {
        $chkUse = $conn->query("SELECT * FROM LichSuDungMaGiamGia WHERE MaNguoiDung=$user_id AND MaGiamGia=$voucher_id");
        if ($chkUse->num_rows > 0) {
            $conn->query("UPDATE LichSuDungMaGiamGia SET SoLan = SoLan + 1 WHERE MaNguoiDung=$user_id AND MaGiamGia=$voucher_id");
        } else {
            $conn->query("INSERT INTO LichSuDungMaGiamGia (MaNguoiDung, MaGiamGia, SoLan) VALUES ($user_id, $voucher_id, 1)");
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    if (isset($conn)) $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>