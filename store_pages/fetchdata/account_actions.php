<?php
// FILE: store_pages/fetchdata/account_actions.php

session_start();
require_once '../../db.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

function sendResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    sendResponse(['success' => false, 'message' => 'Vui lòng đăng nhập', 'require_login' => true]);
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    
    // 1. LẤY HỒ SƠ
    case 'get_profile':
        $sql = "SELECT Ho, Ten, Email, SDT FROM NguoiDung WHERE MaNguoiDung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) sendResponse(['success' => true, 'data' => $row]);
        break;

    // 2. CẬP NHẬT HỒ SƠ
    case 'update_profile':
        $ho = $_POST['ho']; $ten = $_POST['ten']; $sdt = $_POST['sdt'];
        $stmt = $conn->prepare("UPDATE NguoiDung SET Ho=?, Ten=?, SDT=? WHERE MaNguoiDung=?");
        $stmt->bind_param("sssi", $ho, $ten, $sdt, $user_id);
        if ($stmt->execute()) sendResponse(['success' => true, 'message' => 'Đã cập nhật']);
        else sendResponse(['success' => false, 'message' => $conn->error]);
        break;

    // 3. ĐỔI MẬT KHẨU
    case 'change_password':
        $old = $_POST['old_pass']; $new = $_POST['new_pass']; $cfm = $_POST['confirm_pass'];
        if ($new !== $cfm) sendResponse(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        
        $stmt = $conn->prepare("SELECT MatKhau FROM NguoiDung WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        
        if ($row['MatKhau'] !== $old) sendResponse(['success' => false, 'message' => 'Mật khẩu cũ sai']);
        
        $stmt = $conn->prepare("UPDATE NguoiDung SET MatKhau = ? WHERE MaNguoiDung = ?");
        $stmt->bind_param("si", $new, $user_id);
        if ($stmt->execute()) sendResponse(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
        break;

    // 4. LẤY ĐỊA CHỈ
    case 'get_addresses':
        $sql = "SELECT * FROM DiaChiNhanHang WHERE MaNguoiDung = ? ORDER BY LaDiaChiMacDinh DESC, NgayTao DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($r = $res->fetch_assoc()) $data[] = $r;
        sendResponse(['success' => true, 'data' => $data]);
        break;

    // 5. LƯU ĐỊA CHỈ
    case 'save_address':
        $id = $_POST['id'] ?? '';
        $name = $_POST['name']; $phone = $_POST['phone']; $addr = $_POST['address'];
        $is_default = (isset($_POST['is_default']) && $_POST['is_default'] == 'true') ? 'Y' : 'N';

        if ($is_default == 'Y') $conn->query("UPDATE DiaChiNhanHang SET LaDiaChiMacDinh = 'N' WHERE MaNguoiDung = $user_id");

        if ($id) {
            $stmt = $conn->prepare("UPDATE DiaChiNhanHang SET TenNhanHang=?, SDTNhanHang=?, DiaChiNhanHang=?, LaDiaChiMacDinh=? WHERE MaDiaChiNhanHang=? AND MaNguoiDung=?");
            $stmt->bind_param("ssssii", $name, $phone, $addr, $is_default, $id, $user_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO DiaChiNhanHang (MaNguoiDung, TenNhanHang, SDTNhanHang, DiaChiNhanHang, LaDiaChiMacDinh) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $name, $phone, $addr, $is_default);
        }
        if ($stmt->execute()) sendResponse(['success' => true]);
        break;

    // 6. LẤY ĐƠN HÀNG (Kèm MaSPU để tạo link)
    case 'get_orders':
        $status = $_GET['status'] ?? 'all';
        $sql = "SELECT dh.MaDonHang, dh.NgayDat, dh.TongTien, dh.TrangThai, vc.TrangThaiVanChuyen 
                FROM DonHang dh LEFT JOIN DonVanChuyen vc ON dh.MaDonHang = vc.MaDonHang
                WHERE dh.MaNguoiDung = ?";
        if ($status == 'pending') $sql .= " AND dh.TrangThai = 'PENDING'";
        elseif ($status == 'shipping') $sql .= " AND vc.TrangThaiVanChuyen = 'SHIPPING'";
        elseif ($status == 'completed') $sql .= " AND dh.TrangThai = 'COMPLETED'";
        elseif ($status == 'cancelled') $sql .= " AND dh.TrangThai = 'CANCELLED'";
        $sql .= " ORDER BY dh.NgayDat DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];
        while ($order = $result->fetch_assoc()) {
            $oID = $order['MaDonHang'];
            // Join thêm SPU để lấy MaSPU cho link
            $items = $conn->query("SELECT ctdh.SoLuong, ctdh.DonGia, s.TenSanPham, sku.Name as TenBienThe, s.MaSPU,
                                   (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
                                   FROM CTDonHang ctdh JOIN SKU sku ON ctdh.MaSKU = sku.MaSKU JOIN SPU s ON sku.MaSPU = s.MaSPU
                                   WHERE ctdh.MaDonHang = $oID")->fetch_all(MYSQLI_ASSOC);
            $order['Items'] = $items;
            $orders[] = $order;
        }
        sendResponse(['success' => true, 'data' => $orders]);
        break;

    // 7. HỦY ĐƠN HÀNG (Chỉ đơn PENDING)
    case 'cancel_order':
        $order_id = $_POST['order_id'];
        $reason = $_POST['reason'];

        $chk = $conn->query("SELECT TrangThai FROM DonHang WHERE MaDonHang = $order_id AND MaNguoiDung = $user_id");
        if($chk->num_rows == 0) sendResponse(['success'=>false, 'message'=>'Đơn hàng không tồn tại']);
        $row = $chk->fetch_assoc();
        
        if($row['TrangThai'] !== 'PENDING') {
            sendResponse(['success'=>false, 'message'=>'Chỉ có thể hủy đơn đang chờ xác nhận.']);
        }

        $fullReason = "Khách hủy: " . $reason;
        // Cập nhật trạng thái và ghi chú
        $stmt = $conn->prepare("UPDATE DonHang SET TrangThai = 'CANCELLED', GhiChu = CONCAT(IFNULL(GhiChu,''), ' | ', ?) WHERE MaDonHang = ?");
        $stmt->bind_param("si", $fullReason, $order_id);
        
        if($stmt->execute()) sendResponse(['success'=>true]);
        else sendResponse(['success'=>false, 'message'=>$conn->error]);
        break;

    // 8. MUA LẠI (Thêm vào giỏ hàng)
    case 'buy_again':
        $order_id = $_POST['order_id'];
        
        // Lấy danh sách item trong đơn cũ
        $items = $conn->query("SELECT MaSKU, SoLuong FROM CTDonHang WHERE MaDonHang = $order_id");
        if($items->num_rows == 0) sendResponse(['success'=>false, 'message'=>'Đơn hàng lỗi']);

        // Insert vào giỏ hàng (Nếu trùng thì cộng dồn số lượng)
        while($item = $items->fetch_assoc()) {
            $sku = $item['MaSKU'];
            $qty = $item['SoLuong'];
            
            $sql = "INSERT INTO GioHang (MaNguoiDung, MaSKU, SoLuong) VALUES ($user_id, $sku, $qty)
                    ON DUPLICATE KEY UPDATE SoLuong = SoLuong + VALUES(SoLuong)";
            $conn->query($sql);
        }
        sendResponse(['success'=>true]);
        break;

    // 9. LẤY WISHLIST
    case 'get_wishlist':
        $sql = "SELECT s.MaSPU, s.TenSanPham, sku.GiaGoc, 
                (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
                FROM DanhSachYeuThich ds JOIN SKU sku ON ds.MaSKU = sku.MaSKU JOIN SPU s ON sku.MaSPU = s.MaSPU
                WHERE ds.MaNguoiDung = ? ORDER BY ds.NgayThem DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($r = $res->fetch_assoc()) $data[] = $r;
        sendResponse(['success' => true, 'data' => $data]);
        break;

    // 10. TOGGLE TIM (Yêu thích)
    case 'toggle_wishlist':
        $spu_id = $_POST['spu_id'] ?? 0;
        $resSku = $conn->query("SELECT MaSKU FROM SKU WHERE MaSPU = $spu_id LIMIT 1");
        if ($resSku->num_rows == 0) sendResponse(['success' => false, 'message' => 'Lỗi data']);
        $maSKU = $resSku->fetch_assoc()['MaSKU'];

        $check = $conn->query("SELECT * FROM DanhSachYeuThich WHERE MaNguoiDung = $user_id AND MaSKU = $maSKU");
        if ($check->num_rows > 0) {
            $conn->query("DELETE FROM DanhSachYeuThich WHERE MaNguoiDung = $user_id AND MaSKU = $maSKU");
            sendResponse(['success' => true, 'status' => 'removed']);
        } else {
            $conn->query("INSERT INTO DanhSachYeuThich (MaNguoiDung, MaSKU) VALUES ($user_id, $maSKU)");
            sendResponse(['success' => true, 'status' => 'added']);
        }
        break;

    // 11. LẤY ĐÃ XEM GẦN ĐÂY
    case 'get_recent':
        $sql = "SELECT s.MaSPU, s.TenSanPham, sku.GiaGoc, MAX(ls.ThoiDiem) as LanXemCuoi,
                (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
                FROM LichSuHoatDong ls JOIN SKU sku ON ls.MaSKU = sku.MaSKU JOIN SPU s ON sku.MaSPU = s.MaSPU
                WHERE ls.MaNguoiDung = ? GROUP BY s.MaSPU ORDER BY LanXemCuoi DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($r = $res->fetch_assoc()) $data[] = $r;
        sendResponse(['success' => true, 'data' => $data]);
        break;

    // 12. LẤY LỊCH SỬ ĐÁNH GIÁ
    case 'get_reviews_history':
        $type = $_GET['type'] ?? 'not_reviewed';
        if ($type == 'reviewed') {
            $sql = "SELECT dg.SoDiem, dg.BaiViet, dg.NgayDangTai, s.TenSanPham, sku.Name as TenBienThe, s.MaSPU,
                    (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
                    FROM DanhGia dg JOIN SKU sku ON dg.MaSKU = sku.MaSKU JOIN SPU s ON sku.MaSPU = s.MaSPU
                    WHERE dg.MaNguoiDung = ? ORDER BY dg.NgayDangTai DESC";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("i", $user_id);
        } else {
            // Lấy sản phẩm trong đơn COMPLETED mà chưa có trong bảng DanhGia
            $sql = "SELECT ctdh.MaSKU, dh.MaDonHang, s.TenSanPham, sku.Name as TenBienThe, s.MaSPU, dh.NgayDat,
                    (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
                    FROM CTDonHang ctdh JOIN DonHang dh ON ctdh.MaDonHang = dh.MaDonHang 
                    JOIN SKU sku ON ctdh.MaSKU = sku.MaSKU JOIN SPU s ON sku.MaSPU = s.MaSPU
                    WHERE dh.MaNguoiDung = ? AND dh.TrangThai = 'COMPLETED'
                    AND ctdh.MaSKU NOT IN (SELECT MaSKU FROM DanhGia WHERE MaNguoiDung = ?)
                    ORDER BY dh.NgayDat DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($r = $res->fetch_assoc()) $data[] = $r;
        sendResponse(['success' => true, 'data' => $data]);
        break;

    default: sendResponse(['success' => false, 'message' => 'Invalid Action']);
}
?>