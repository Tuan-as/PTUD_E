<?php
include '../fetch/Admin_GetData.php'; // Đảm bảo đường dẫn này đúng
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();
// Lấy ID admin từ session, nếu không có thì mặc định là 6 (dựa theo dữ liệu mẫu của bạn)
$adminId = $_SESSION['admin_id'] ?? 1;

$maSKU   = $_POST['MaSKU'] ?? '';
$loai    = $_POST['LoaiThayDoi'] ?? '';
$soLuong = (int)($_POST['SoLuong'] ?? 0);
$ghiChu  = $_POST['GhiChu'] ?? '';

// Kiểm tra dữ liệu đầu vào cơ bản
if (empty($maSKU) || $soLuong <= 0 || empty($loai)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu nhập vào không hợp lệ.']);
    exit;
}

try {
    // 1. Kiểm tra xem SKU có tồn tại không trước khi chèn
    $checkSKU = $pdo->prepare("SELECT TonKho FROM SKU WHERE MaSKU = ?");
    $checkSKU->execute([$maSKU]);
    $sku = $checkSKU->fetch();

    if (!$sku) {
        throw new Exception("Sản phẩm (MaSKU: $maSKU) không tồn tại trong hệ thống.");
    }

    // 2. Kiểm tra nếu là xuất kho thì có đủ hàng để trừ không
    if ($loai === 'Xuất kho' && $sku['TonKho'] < $soLuong) {
        throw new Exception("Không đủ hàng trong kho. Hiện còn: " . $sku['TonKho']);
    }

    // 3. CHỈ CHÈN VÀO BẢNG LỊCH SỬ
    // Database Trigger trg_lstdk_after_insert sẽ tự động cập nhật bảng SKU cho bạn.
    $insertLog = $pdo->prepare("
        INSERT INTO lichsuthaydoitonkho (MaSKU, LoaiThayDoi, SoLuong, NguoiThucHien, NgayThucHien, GhiChu)
        VALUES (?, ?, ?, ?, NOW(), ?)
    ");
    
    $result = $insertLog->execute([$maSKU, $loai, $soLuong, $adminId, $ghiChu]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Lưu phiếu thành công. Hệ thống đã tự cập nhật tồn kho.']);
    } else {
        throw new Exception("Không thể lưu dữ liệu vào bảng lịch sử.");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>