<?php
header('Content-Type: application/json');
include '../includes/db.php';

if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID không xác định']);
    exit;
}

$id = (int)$_POST['id'];

try {
    $pdo->beginTransaction();
    // Xoá liên kết thuộc tính trước
    $pdo->prepare("DELETE FROM ThuocTinhSanPham WHERE MaSKU = ?")->execute([$id]);
    // Xoá SKU
    $pdo->prepare("DELETE FROM SKU WHERE MaSKU = ?")->execute([$id]);
    
    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}