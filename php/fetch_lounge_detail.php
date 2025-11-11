<?php
// file: fetch_lounge_detail.php - API Lấy dữ liệu chi tiết 1 bài Lounge theo ID

header('Content-Type: application/json; charset=utf-8');

// 1. Cấu hình Database
$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db"; // Tên database

// Lấy ID từ URL và kiểm tra
$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($articleId === 0) {
    // Báo lỗi nếu ID thiếu
    echo json_encode(['error' => 'Thiếu ID bài viết.']);
    exit;
}

// 2. Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
// Thiết lập charset để hỗ trợ tiếng Việt
$conn->set_charset("utf8mb4");

// Kiểm tra kết nối
if ($conn->connect_error) {
    // Trả về lỗi nếu kết nối thất bại
    echo json_encode(['error' => 'Lỗi kết nối MySQL: ' . $conn->connect_error]);
    exit;
}

// 3. Truy vấn lấy chi tiết bài viết (Đã bao gồm 3 cột ảnh)
$sql_query = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') as date, author, category, image_url as imageUrl, full_content as content, content_image_1 as img1, content_image_2 as img2, content_image_3 as img3 
              FROM lounge_articles 
              WHERE id = $articleId";

$result = $conn->query($sql_query);
$response = [];

if ($result === FALSE) {
    // Nếu lỗi SQL, báo lỗi cụ thể
    $response = ['error' => 'Lỗi truy vấn SQL: ' . $conn->error];
} else if ($result->num_rows > 0) {
    // Thành công
    $response = $result->fetch_assoc();
} else {
    // Không tìm thấy bài viết
    $response = ['error' => 'Không tìm thấy bài viết Lounge.'];
}

$conn->close();

// 4. Trả về kết quả JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>