<?php
// - Lấy 5 bài Lounge mới nhất

header('Content-Type: application/json; charset=utf-8');

// Thiết lập thông tin kết nối Database
$servername = "localhost"; $username = "root"; $password = ""; $dbname = "CauChuyen_db"; 

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // Xử lý lỗi kết nối
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// Truy vấn: Lấy 5 bài Lounge mới nhất (dùng cho slider kéo ngang)
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, author, category as category_slug, image_url 
        FROM lounge_articles 
        ORDER BY post_date DESC 
        LIMIT 5";

$result = $conn->query($sql);
$articles_data = [];

if ($result && $result->num_rows > 0) {
    // Lặp qua kết quả và định dạng dữ liệu
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

// Đóng kết nối và trả về JSON
$conn->close();
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE);
?>