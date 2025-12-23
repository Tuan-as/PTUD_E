<?php
header('Content-Type: application/json');
include '../includes/db.php'; // đã có $pdo sẵn

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID không xác định']);
    exit;
}

$id = (int)$_POST['id'];

try {
    // Xoá SKU liên quan trước nếu muốn
    $stmtSKU = $pdo->prepare("DELETE FROM SKU WHERE MaSPU = ?");
    $stmtSKU->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM SPU WHERE MaSPU = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
