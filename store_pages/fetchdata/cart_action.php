<?php
// FILE: store_pages/fetchdata/cart_action.php

session_start();
require_once '../../db.php'; 

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Tắt lỗi hiển thị html để tránh hỏng JSON

/* --- KIỂM TRA ĐĂNG NHẬP (BẮT BUỘC) --- */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'require_login' => true, 
        'message' => 'Vui lòng đăng nhập để thực hiện chức năng này'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_REQUEST['action'] ?? ''; // Dùng REQUEST để nhận cả GET và POST

/* =================================================================
   1. THÊM VÀO GIỎ HÀNG (ADD)
   ================================================================= */
if ($action === 'add') {
    $sku_id = intval($_POST['sku_id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);

    if ($sku_id <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    // Kiểm tra tồn kho
    $sqlStock = "SELECT TonKho FROM SKU WHERE MaSKU = $sku_id AND TrangThai = 'ACTIVE'";
    $resStock = $conn->query($sqlStock);
   
    if ($resStock->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc ngừng kinh doanh']);
        exit;
    }
   
    $stock = $resStock->fetch_assoc()['TonKho'];
    
    // Kiểm tra trong giỏ
    $checkCart = $conn->query("SELECT SoLuong FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");

    if ($checkCart->num_rows > 0) {
        // Cộng dồn
        $currentQty = $checkCart->fetch_assoc()['SoLuong'];
        $newQty = $currentQty + $qty;

        if ($newQty > $stock) {
            echo json_encode(['success' => false, 'message' => "Kho chỉ còn $stock sản phẩm. (Giỏ hàng đã có $currentQty)"]);
            exit;
        }

        $conn->query("UPDATE GioHang SET SoLuong = $newQty WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");
    } else {
        // Thêm mới
        if ($qty > $stock) {
            echo json_encode(['success' => false, 'message' => "Kho chỉ còn $stock sản phẩm"]);
            exit;
        }
        $conn->query("INSERT INTO GioHang (MaNguoiDung, MaSKU, SoLuong) VALUES ($user_id, $sku_id, $qty)");
    }
    
    echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
    exit;
}

/* =================================================================
   2. LẤY DANH SÁCH GIỎ HÀNG (GET)
   ================================================================= */
if ($action === 'get') {
    // JOIN thêm bảng SPU để lấy MaSPU (cho link) và TenSanPham
    $sql = "SELECT gh.MaSKU, gh.SoLuong, 
            sku.GiaGoc, sku.GiaGiam, sku.TonKho, sku.Name as TenBienThe,
            s.TenSanPham, s.MaSPU, 
            (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = sku.MaSKU LIMIT 1) as HinhAnh
            FROM GioHang gh
            JOIN SKU sku ON gh.MaSKU = sku.MaSKU
            JOIN SPU s ON sku.MaSPU = s.MaSPU
            WHERE gh.MaNguoiDung = $user_id
            ORDER BY gh.NgayThem DESC";
            
    $result = $conn->query($sql);
    $data = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Tính giá ưu tiên (Nếu có giảm giá thì lấy giá giảm)
            $row['GiaHienTai'] = ($row['GiaGiam'] > 0 && $row['GiaGiam'] < $row['GiaGoc']) ? $row['GiaGiam'] : $row['GiaGoc'];
            $data[] = $row;
        }
    }
    
    echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

/* =================================================================
   3. CẬP NHẬT SỐ LƯỢNG (UPDATE)
   ================================================================= */
if ($action === 'update') {
    $sku_id = intval($_POST['sku_id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);

    if ($sku_id <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        exit;
    }

    // Check tồn kho trước khi update
    $resStock = $conn->query("SELECT TonKho FROM SKU WHERE MaSKU = $sku_id");
    if ($resStock->num_rows > 0) {
        $stock = $resStock->fetch_assoc()['TonKho'];
        if ($qty > $stock) {
            echo json_encode(['success' => false, 'message' => "Vượt quá tồn kho ($stock)"]);
            exit;
        }
    }

    $sql = "UPDATE GioHang SET SoLuong = $qty WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

/* =================================================================
   4. XÓA 1 SẢN PHẨM (REMOVE)
   ================================================================= */
if ($action === 'remove') {
    $sku_id = intval($_POST['sku_id'] ?? 0);
    
    $sql = "DELETE FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

/* =================================================================
   5. XÓA NHIỀU SẢN PHẨM ĐÃ CHỌN (REMOVE LIST)
   ================================================================= */
if ($action === 'remove_list') {
    // Nhận mảng SKU ID từ client (dạng chuỗi: "1,2,3")
    $list_sku = $_POST['list_sku'] ?? '';
    
    if (!empty($list_sku)) {
        // Chuyển thành mảng số nguyên để bảo mật
        $ids = array_map('intval', explode(',', $list_sku));
        $ids_str = implode(',', $ids);
        
        $sql = "DELETE FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU IN ($ids_str)";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Chưa chọn sản phẩm']);
    }
    exit;
}

// Nếu không khớp action nào
echo json_encode(['success' => false, 'message' => 'Invalid Action']);
?>