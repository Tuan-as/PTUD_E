<?php
include '../includes/db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? '';

if ($id > 0 && !empty($status)) {
    try {
        $stmt = $pdo->prepare("UPDATE DonHang SET TrangThai = ? WHERE MaDonHang = ?");
        $stmt->execute([$status, $id]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
}