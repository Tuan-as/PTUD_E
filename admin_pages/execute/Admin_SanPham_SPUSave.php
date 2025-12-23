<?php
include '../includes/db.php';
session_start();

$user = $_SESSION['admin_id'] ?? 1;

// Kiểm tra POST
$ten = $_POST['TenSanPham'] ?? '';
$motangan = $_POST['MoTaNgan'] ?? '';
$motadai = $_POST['MoTaDai'] ?? '';
$madm = $_POST['MaDanhMuc'] ?? '';
$maSPU = $_POST['MaSPU'] ?? '';

if (!$ten) {
    die("Tên sản phẩm không được để trống!");
}

if (!$maSPU) {
    // Thêm mới
    $stmt = $pdo->prepare("
        INSERT INTO SPU
        (TenSanPham, MoTaNgan, MoTaDai, MaDanhMuc, NguoiTao, NguoiChinhSuaCuoi)
        VALUES (?,?,?,?,?,?)
    ");
    $stmt->execute([$ten, $motangan, $motadai, $madm, $user, $user]);
} else {
    // Cập nhật
    $stmt = $pdo->prepare("
        UPDATE SPU SET
            TenSanPham=?,
            MoTaNgan=?,
            MoTaDai=?,
            MaDanhMuc=?,
            NguoiChinhSuaCuoi=?,
            NgayChinhSua=NOW()
        WHERE MaSPU=?
    ");
    $stmt->execute([$ten, $motangan, $motadai, $madm, $user, $maSPU]);
}

header('Location: ../Admin_SanPham.php');
exit;
