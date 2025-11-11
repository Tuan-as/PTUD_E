<?php
// API Lấy Danh Sách Sự Kiện và Tính Trạng Thái

header('Content-Type: application/json; charset=utf-8');

// 1. Cấu hình Database
$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db"; // Tên database

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // Xử lý lỗi kết nối
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 2. Hàm tính trạng thái sự kiện
function calculateStatus($startDate, $endDate) {
    // Lấy ngày hiện tại thực tế
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

// 3. Truy vấn: Lấy tất cả sự kiện
$sql = "SELECT id, title, description, event_date_range, location, start_date, end_date, image_url 
        FROM events 
        ORDER BY start_date DESC";

$result = $conn->query($sql);
$events_data = [];

if ($result && $result->num_rows > 0) {
    // Lặp qua kết quả, tính toán và định dạng trạng thái
    while($row = $result->fetch_assoc()) {
        $status = calculateStatus($row['start_date'], $row['end_date']);
        
        // Tạo nhãn tiếng Việt cho trạng thái
        if ($status == 'current') {
            $status_label = 'Đang diễn ra';
        } elseif ($status == 'upcoming') {
            $status_label = 'Sắp diễn ra';
        } else {
            $status_label = 'Đã diễn ra';
        }
        
        $event = [
            "id" => $row["id"], 
            "title" => $row["title"],
            "description" => $row["description"],
            "date_range" => $row["event_date_range"],
            "location" => $row["location"],
            "status_slug" => $status, // Slug (past, current, upcoming) dùng để lọc trong JS
            "image_url" => $row["image_url"],
            "status_label" => $status_label, // Nhãn hiển thị
        ];
        array_push($events_data, $event);
    }
}

$conn->close();
// 4. Trả về kết quả JSON
echo json_encode($events_data, JSON_UNESCAPED_UNICODE);
?>