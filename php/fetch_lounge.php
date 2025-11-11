<?php
// file: fetch_lounge.php - API Lấy toàn bộ danh sách bài viết Lounge

// 1. Cấu hình kết nối Database
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "CauChuyen_db"; 

// Thiết lập Header phản hồi JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Xử lý lỗi kết nối MySQL
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 3. Truy vấn: Lấy toàn bộ dữ liệu bài Lounge
// Chú ý: Cột 'category' chứa giá trị Tiếng Việt được dùng cho logic lọc trong JS
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, author, category, image_url 
        FROM lounge_articles 
        ORDER BY post_date DESC";

$result = $conn->query($sql);

$articles_data = array();

if ($result->num_rows > 0) {
    // Lặp qua kết quả và định dạng dữ liệu
    while($row = $result->fetch_assoc()) {
        $article = [
            "id" => $row["id"], 
            "category_slug" => $row["category"], // Giá trị Tiếng Việt (category) được dùng làm slug để khớp với CATEGORY_MAP trong JS
            "title" => $row["title"],
            "description" => $row["description"],
            "date" => $row["date"],
            "author" => $row["author"], 
            "imageUrl" => $row["image_url"]
        ];
        array_push($articles_data, $article);
    }
} 

// 4. Đóng kết nối và trả về JSON
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE); 
$conn->close();
?>