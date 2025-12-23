<?php
// FILE: API LẤY 5 BÀI LOUNGE MỚI NHẤT CHO TRANG CHỦ

header('Content-Type: application/json; charset=utf-8');

// 1. CẤU HÌNH KẾT NỐI DATABASE
// sử dụng đường dẫn tương đối để gọi file config ở root nếu có,
// hoặc định nghĩa trực tiếp ở đây. 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "CauChuyen_db"; 

// 2. KẾT NỐI MYSQL
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // xử lý lỗi kết nối
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 3. TRUY VẤN: LẤY 5 BÀI MỚI NHẤT
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, author, category as category_slug, image_url 
        FROM lounge_articles 
        ORDER BY post_date DESC 
        LIMIT 5";

$result = $conn->query($sql);
$articles_data = [];

if ($result && $result->num_rows > 0) {
    // lặp qua kết quả
    while($row = $result->fetch_assoc()) {
        $article = [
            "id" => $row["id"], 
            "title" => $row["title"],
            "description" => $row["description"],
            "date" => $row["date"],
            "author" => $row["author"],
            "category_slug" => $row["category_slug"],
            "imageUrl" => $row["image_url"]
        ];
        array_push($articles_data, $article);
    }
} 

// 4. TRẢ VỀ JSON
$conn->close();
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE);
?>