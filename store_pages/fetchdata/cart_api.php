<?php
session_start();
require_once '../../db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// --- LOAD GIỎ HÀNG ---
if ($action === 'load') {
    // Lấy thông tin giỏ hàng + Giá + Tồn kho + Ảnh đại diện
    $sql = "SELECT g.MaSKU, g.SoLuong, s.Name, s.GiaGoc, s.GiaGiam, s.TonKho, s.SKUCode, spu.TenSanPham,
            (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia WHERE ms.MaSKU = s.MaSKU ORDER BY ms.VaiTro ASC LIMIT 1) as HinhAnh
            FROM GioHang g 
            JOIN SKU s ON g.MaSKU = s.MaSKU
            JOIN SPU spu ON s.MaSPU = spu.MaSPU
            WHERE g.MaNguoiDung = $user_id";
    
    $result = $conn->query($sql);
    $items = [];
    
    while ($row = $result->fetch_assoc()) {
        // Tính giá bán thực tế
        $row['GiaBan'] = floatval($row['GiaGiam'] > 0 ? $row['GiaGiam'] : $row['GiaGoc']);
        $row['HinhAnh'] = $row['HinhAnh'] ? $row['HinhAnh'] : 'no-image.png';
        $items[] = $row;
    }
    echo json_encode(['success' => true, 'items' => $items]);
    exit;
}

// --- CẬP NHẬT SỐ LƯỢNG ---
if ($action === 'update') {
    $sku_id = intval($_POST['sku_id']);
    $qty = intval($_POST['qty']);
    
    // Kiểm tra tồn kho thực tế
    $stockCheck = $conn->query("SELECT TonKho FROM SKU WHERE MaSKU = $sku_id")->fetch_assoc();
    if ($qty > $stockCheck['TonKho']) {
        echo json_encode(['success' => false, 'message' => 'Kho chỉ còn ' . $stockCheck['TonKho'] . ' sản phẩm']);
        exit;
    }

    if ($qty <= 0) {
        $conn->query("DELETE FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");
    } else {
        $conn->query("UPDATE GioHang SET SoLuong = $qty WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");
    }
    echo json_encode(['success' => true]);
    exit;
}

// --- XÓA SẢN PHẨM ---
if ($action === 'remove') {
    $sku_id = intval($_POST['sku_id']);
    $conn->query("DELETE FROM GioHang WHERE MaNguoiDung = $user_id AND MaSKU = $sku_id");
    echo json_encode(['success' => true]);
    exit;
}
?>