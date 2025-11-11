<?php
// - API Lấy toàn bộ danh sách Tin Tức

// Thiết lập thông tin kết nối Database
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "CauChuyen_db"; 

// Thiết lập header phản hồi JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Xử lý lỗi kết nối
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Truy vấn: Lấy toàn bộ dữ liệu bài báo
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, category, image_url 
        FROM articles 
        ORDER BY post_date DESC";

$result = $conn->query($sql);

$articles_data = array();

if ($result->num_rows > 0) {
    // Lặp qua kết quả và định dạng dữ liệu
    while($row = $result->fetch_assoc()) {
        $article = [
            "id" => $row["id"], 
            // Tạo tag dựa trên category
            "tag" => ($row["category"] == "product" ? "Sản phẩm" : "Công ty"), 
            "category" => $row["category"],
            "title" => $row["title"],
            "description" => $row["description"],
            "date" => $row["date"],
            "imageUrl" => $row["image_url"]
        ];
        array_push($articles_data, $article);
    }
} 

// Đóng kết nối và trả về JSON
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE); 
$conn->close();
?>