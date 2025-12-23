<?php
// FILE: LocknLock/db.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "locknlock_store"; // Tên DB chuẩn theo SQL bạn gửi

// Bật báo lỗi chi tiết để debug nếu kết nối sai
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    mysqli_set_charset($conn, "utf8mb4");
    // Tắt strict mode để tránh lỗi query ở trang sản phẩm
    mysqli_query($conn, "SET sql_mode = ''");
} catch (mysqli_sql_exception $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>