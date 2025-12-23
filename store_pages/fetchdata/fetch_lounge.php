<?php
// FILE: fetch_lounge.php - API LẤY DANH SÁCH BÀI VIẾT LOUNGE

// 1. CẤU HÌNH KẾT NỐI DATABASE
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "CauChuyen_db"; 

// thiết lập header trả về json
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. KẾT NỐI DATABASE
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // xử lý lỗi kết nối
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 3. TRUY VẤN DỮ LIỆU
// lấy toàn bộ bài viết, sắp xếp mới nhất
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, author, category, image_url 
        FROM lounge_articles 
        ORDER BY post_date DESC";

$result = $conn->query($sql);

$articles_data = array();

if ($result->num_rows > 0) {
    // lặp qua kết quả và đưa vào mảng
    while($row = $result->fetch_assoc()) {
        $article = [
            "id" => $row["id"], 
            "category_slug" => $row["category"], 
            "title" => $row["title"],
            "description" => $row["description"],
            "date" => $row["date"],
            "author" => $row["author"], 
            "imageUrl" => $row["image_url"]
        ];
        array_push($articles_data, $article);
    }
} 

// 4. ĐÓNG KẾT NỐI VÀ TRẢ VỀ JSON
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE); 
$conn->close();
?>