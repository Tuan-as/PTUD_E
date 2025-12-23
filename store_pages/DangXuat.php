<?php
session_start();
session_destroy(); // Xóa sạch session
header("Location: DangNhap.php"); // Quay về trang đăng nhập
exit;
?>