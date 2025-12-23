<?php
// FILE: Admin_pages/Admin_DangXuat.php
session_start();
session_unset();
session_destroy();

// Sau khi đăng xuất, đưa về trang đăng nhập
header("Location: Admin_DangNhap.php");
exit;