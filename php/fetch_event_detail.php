<?php
// file: fetch_event_detail.php - API Lấy Chi Tiết Sự Kiện

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
    echo json_encode(['error' => 'Thiếu ID sự kiện.']);
    exit;
}

// 2. Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // Trả về lỗi nếu kết nối thất bại
    echo json_encode(['error' => 'Lỗi kết nối MySQL: ' . $conn->connect_error]);
    exit;
}

// 3. Hàm tính trạng thái sự kiện
function calculateStatus($startDate, $endDate) {
    $today = strtotime(date('Y-m-d')); 
    $start = strtotime($startDate);
    $end = strtotime($endDate);

    if ($end < $today) {
        return 'past'; // Đã diễn ra
    } elseif ($start <= $today && $end >= $today) {
        return 'current'; // Đang diễn ra
    } else {
        return 'upcoming'; // Sắp diễn ra
    }
}

// 4. Truy vấn (Lấy full_content và các trường khác)
$sql = "SELECT id, title, description, event_date_range, location, start_date, end_date, image_url as imageUrl, full_content as content 
        FROM events 
        WHERE id = $articleId";

$result = $conn->query($sql);
$response = [];

if ($result && $result->num_rows > 0) {
    // Lấy dữ liệu sự kiện
    $event = $result->fetch_assoc();
    // Tính toán trạng thái
    $status = calculateStatus($event['start_date'], $event['end_date']);
    
    // Gán nhãn trạng thái tiếng Việt
    if ($status == 'current') {
        $status_label = 'Đang diễn ra';
    } elseif ($status == 'upcoming') {
        $status_label = 'Sắp diễn ra';
    } else {
        $status_label = 'Đã diễn ra';
    }

    // Định dạng dữ liệu phản hồi
    $response = [
        'id' => $event['id'],
        'title' => $event['title'],
        'description' => $event['description'],
        'date_range' => $event['event_date_range'],
        'location' => $event['location'],
        'imageUrl' => $event['imageUrl'],
        'content' => $event['content'],
        'status_slug' => $status, // Slug để JS định dạng CSS
        'status_label' => $status_label // Nhãn để JS hiển thị
    ];

} else {
    // Không tìm thấy sự kiện
    $response = ['error' => 'Không tìm thấy sự kiện.'];
}

$conn->close();
// 5. Trả về kết quả JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>