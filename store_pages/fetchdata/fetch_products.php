<?php
// FILE: store_pages/fetchdata/fetch_products.php

// 1. CẤU HÌNH DATABASE
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "locknlock_store"; 

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

error_reporting(0);
ini_set('display_errors', 0);

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Lỗi kết nối DB: " . $conn->connect_error]));
}

// Tắt Strict Mode
$conn->query("SET sql_mode = ''");

// NHẬN THAM SỐ
$category_id = isset($_GET['category']) && $_GET['category'] != 'all' ? intval($_GET['category']) : null;
$min = isset($_GET['min']) ? floatval($_GET['min']) : 0;
$max = isset($_GET['max']) ? floatval($_GET['max']) : 999999999;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// --- PHẦN QUAN TRỌNG: CÂU TRUY VẤN ĐÃ SỬA ---
$sql = "SELECT 
            s.MaSPU, 
            s.TenSanPham, 
            d.TenDanhMuc,
            s.NgayTao,
            SUM(sk.TonKho) as TongTonKho,
            MIN(COALESCE(sk.GiaGiam, sk.GiaGoc)) as GiaHienThi,
            MAX(sk.GiaGoc) as GiaGoc,
            (
                SELECT m.File 
                FROM Media m
                JOIN MediaSanPham ms ON m.MaMedia = ms.MaMedia
                JOIN SKU sk2 ON ms.MaSKU = sk2.MaSKU
                WHERE sk2.MaSPU = s.MaSPU 
                AND ms.VaiTro = 'daidien'  -- <--- THÊM ĐIỀU KIỆN NÀY
                LIMIT 1
            ) as HinhAnh
        FROM SPU s
        JOIN SKU sk ON s.MaSPU = sk.MaSPU
        LEFT JOIN DanhMucSanPham d ON s.MaDanhMuc = d.MaDanhMuc
        WHERE sk.TrangThai = 'ACTIVE' ";

if ($category_id) {
    $sql .= " AND s.MaDanhMuc = $category_id ";
}

$sql .= " GROUP BY s.MaSPU "; 
$sql .= " HAVING GiaHienThi >= $min AND GiaHienThi <= $max ";

// Sắp xếp
switch ($sort) {
    case 'price_asc': $sql .= " ORDER BY GiaHienThi ASC"; break;
    case 'price_desc': $sql .= " ORDER BY GiaHienThi DESC"; break;
    case 'newest': default: $sql .= " ORDER BY s.NgayTao DESC"; break;
}

$result = $conn->query($sql);
$products = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi truy vấn SQL: " . $conn->error]);
}
$conn->close();
?>