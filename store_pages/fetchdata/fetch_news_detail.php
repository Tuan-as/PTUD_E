<?php
// FILE: API LẤY CHI TIẾT 1 BÀI TIN TỨC THEO ID

// 1. CẤU HÌNH KẾT NỐI
$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db";

// thiết lập header json
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// lấy id từ url
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die(json_encode(["error" => "ID bài báo không hợp lệ hoặc bị thiếu."]));
}
$article_id = intval($_GET['id']);

// 2. KẾT NỐI MYSQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 3. TRUY VẤN DỮ LIỆU (DÙNG PREPARED STATEMENT)
$sql = $conn->prepare("SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, category, image_url, full_content 
                       FROM articles 
                       WHERE id = ?");
                       
if (!$sql) {
    die(json_encode(["error" => "Lỗi Prepared Statement: " . $conn->error]));
}

// thực thi truy vấn
$sql->bind_param("i", $article_id);
$sql->execute();
$result = $sql->get_result();

$article_detail = ["error" => "Không tìm thấy bài báo với ID: " . $article_id];

// 4. XỬ LÝ KẾT QUẢ
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $article_detail = [
        "id" => $row["id"],
        "tag" => ($row["category"] == "product" ? "Sản phẩm" : "Công ty"),
        "category" => $row["category"],
        "title" => $row["title"],
        "description" => $row["description"], 
        "date" => $row["date"],
        "imageUrl" => $row["image_url"],
        "content" => $row["full_content"] 
    ];
}

echo json_encode($article_detail, JSON_UNESCAPED_UNICODE);
$conn->close();
?>