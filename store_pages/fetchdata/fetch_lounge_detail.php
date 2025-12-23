<?php
// FILE: fetch_lounge_detail.php - API LẤY CHI TIẾT BÀI LOUNGE

// 1. CẤU HÌNH HEADER VÀ DATABASE
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db"; 

// lấy id từ url
$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($articleId === 0) {
    // lỗi nếu thiếu id
    echo json_encode(['error' => 'Thiếu ID bài viết.']);
    exit;
}

// 2. TẠO KẾT NỐI
$conn = new mysqli($servername, $username, $password, $dbname);
// thiết lập charset tiếng việt
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Lỗi kết nối MySQL: ' . $conn->connect_error]);
    exit;
}

$info = $conn->query($sqlInfo)->fetch_assoc();

if (!$info) {
   sendResponse(['error' => 'Không tìm thấy sản phẩm với ID này']);
}

// === [BỔ SUNG] LƯU LỊCH SỬ HOẠT ĐỘNG (XEM SẢN PHẨM) ===
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    
    // Lấy đại diện 1 SKU của sản phẩm này để lưu (Vì bảng LichSuHoatDong yêu cầu MaSKU)
    // Nếu bạn muốn lưu chính xác SKU nào khách click thì phải gửi từ JS, nhưng ở trang detail mặc định ta lấy cái đầu tiên
    $checkSku = $conn->query("SELECT MaSKU FROM SKU WHERE MaSPU = $id LIMIT 1");
    if ($checkSku->num_rows > 0) {
        $rSku = $checkSku->fetch_assoc();
        $skuId = $rSku['MaSKU'];
        
        // Dùng INSERT IGNORE hoặc ON DUPLICATE KEY UPDATE để cập nhật thời gian xem mới nhất
        $conn->query("INSERT INTO LichSuHoatDong (MaNguoiDung, MaSKU, ThoiDiem) 
                      VALUES ($uid, $skuId, NOW()) 
                      ON DUPLICATE KEY UPDATE ThoiDiem = NOW()");
    }
}

// 3. TRUY VẤN CHI TIẾT BÀI VIẾT
// lấy thông tin bài viết và 3 cột ảnh nội dung
$sql_query = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') as date, author, category, image_url as imageUrl, full_content as content, content_image_1 as img1, content_image_2 as img2, content_image_3 as img3 
              FROM lounge_articles 
              WHERE id = $articleId";

$result = $conn->query($sql_query);
$response = [];

if ($result === FALSE) {
    // lỗi sql
    $response = ['error' => 'Lỗi truy vấn SQL: ' . $conn->error];
} else if ($result->num_rows > 0) {
    // tìm thấy dữ liệu
    $response = $result->fetch_assoc();
} else {
    // không tìm thấy bài
    $response = ['error' => 'Không tìm thấy bài viết Lounge.'];
}

$conn->close();

// 4. TRẢ VỀ KẾT QUẢ JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>