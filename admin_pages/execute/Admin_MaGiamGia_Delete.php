<?php
include '../fetch/Admin_GetData.php'; // Đường dẫn từ execute/ ra ngoài rồi vào includes/
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

if ($id) {
    try {
        // Kiểm tra xem mã này có đang được sử dụng ở bảng LichSuDungMaGiamGia không
        // Nếu có khóa ngoại, bạn có thể cần xóa lịch sử trước hoặc báo lỗi
        $stmt = $pdo->prepare("DELETE FROM MaGiamGia WHERE MaGiamGia = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Nếu lỗi do khóa ngoại (đã có khách dùng mã), trả về thông báo
        echo json_encode(['success' => false, 'message' => "Không thể xóa mã này vì đã có lịch sử sử dụng!"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Không nhận được ID để xóa"]);
}