<?php
// FILE: Admin_pages/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền: Nếu không có session admin, đẩy về trang đăng nhập ngay lập tức
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'ADMIN') {
    header("Location: Admin_DangNhap.php");
    exit;
}

$userName = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-3">
    <button class="btn btn-outline-light d-md-none" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        ☰
    </button>

    <span class="navbar-brand ms-2">LocknLock Admin</span>

    <div class="ms-auto d-flex align-items-center">
        <span class="text-white small me-3">
            Xin chào, <strong><?= htmlspecialchars($userName) ?></strong>
        </span>
        <a href="Admin_DangXuat.php" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>