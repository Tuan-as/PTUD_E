<?php
// FILE: API LẤY TOÀN BỘ DANH SÁCH TIN TỨC

// 1. CẤU HÌNH KẾT NỐI
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "CauChuyen_db"; 

// thiết lập header trả về json
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. KẾT NỐI MYSQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // trả về lỗi kết nối
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// 3. TRUY VẤN DỮ LIỆU
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, category, image_url 
        FROM articles 
        ORDER BY post_date DESC";

$result = $conn->query($sql);

$articles_data = array();

if ($result->num_rows > 0) {
    // lặp qua kết quả và định dạng
    while($row = $result->fetch_assoc()) {
        $article = [
            "id" => $row["id"], 
            // tạo nhãn hiển thị dựa trên category
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

// 4. TRẢ VỀ JSON
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE); 
$conn->close();
?>