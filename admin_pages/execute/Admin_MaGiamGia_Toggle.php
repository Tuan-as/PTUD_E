<?php
include '../fetch/Admin_GetData.php';
$id = $_POST['id'];
$pdo->prepare("UPDATE MaGiamGia SET TrangThai = IF(TrangThai='ACTIVE', 'INACTIVE', 'ACTIVE') WHERE MaGiamGia = ?")->execute([$id]);
echo json_encode(['success' => true]);