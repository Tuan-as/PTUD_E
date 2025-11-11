<?php
// file: fetch_news_detail.php - API Lấy dữ liệu chi tiết 1 bài Tin Tức theo ID

// 1. Cấu hình kết nối Database
$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db";

// Thiết lập Header để trả về dữ liệu JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. Lấy ID từ URL (GET parameter)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Trả về lỗi nếu ID không hợp lệ
    die(json_encode(["error" => "ID bài báo không hợp lệ hoặc bị thiếu."]));
}
// Chuyển ID sang dạng số nguyên an toàn
$article_id = intval($_GET['id']);

// 3. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Nếu lỗi kết nối, trả về lỗi chi tiết.
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 4. Truy vấn dữ liệu chi tiết bằng Prepared Statement
$sql = $conn->prepare("SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, category, image_url, full_content 
                       FROM articles 
                       WHERE id = ?");
                       
// Kiểm tra lỗi chuẩn bị câu lệnh
if (!$sql) {
    die(json_encode(["error" => "Lỗi Prepared Statement: " . $conn->error]));
}

// Gán tham số và thực thi
$sql->bind_param("i", $article_id);
$sql->execute();
$result = $sql->get_result();

$article_detail = ["error" => "Không tìm thấy bài báo với ID: " . $article_id];

// 5. Lấy dữ liệu và trả về JSON
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $article_detail = [
        "id" => $row["id"],
        // Tạo tag
        "tag" => ($row["category"] == "product" ? "Sản phẩm" : "Công ty"),
        "category" => $row["category"],
        "title" => $row["title"],
        "description" => $row["description"], 
        "date" => $row["date"],
        "imageUrl" => $row["image_url"],
        "content" => $row["full_content"] // Nội dung chi tiết
    ];
}

echo json_encode($article_detail, JSON_UNESCAPED_UNICODE);
$conn->close();
?>