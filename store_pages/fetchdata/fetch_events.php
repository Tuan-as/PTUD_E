<?php
// API LẤY DANH SÁCH SỰ KIỆN VÀ TÍNH TRẠNG THÁI

header('Content-Type: application/json; charset=utf-8');

// 1. CẤU HÌNH KẾT NỐI DATABASE
$servername = "localhost"; 
$username = "root";       
$password = "";          
$dbname = "CauChuyen_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // xử lý lỗi kết nối database
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// 2. HÀM TÍNH TRẠNG THÁI SỰ KIỆN DỰA TRÊN NGÀY
function calculateStatus($startDate, $endDate) {
    // lấy ngày hiện tại
    $today = strtotime(date('Y-m-d')); 
    
    $start = strtotime($startDate);
    $end = strtotime($endDate);

    if ($end < $today) {
        return 'past'; // đã diễn ra
    } elseif ($start <= $today && $end >= $today) {
        return 'current'; // đang diễn ra
    } else {
        return 'upcoming'; // sắp diễn ra
    }
}

// 3. TRUY VẤN LẤY TẤT CẢ SỰ KIỆN
$sql = "SELECT id, title, description, event_date_range, location, start_date, end_date, image_url 
        FROM events 
        ORDER BY start_date DESC";

$result = $conn->query($sql);
$events_data = [];

if ($result && $result->num_rows > 0) {
    // lặp qua từng dòng kết quả
    while($row = $result->fetch_assoc()) {
        // tính toán trạng thái
        $status = calculateStatus($row['start_date'], $row['end_date']);
        
        // tạo nhãn hiển thị tiếng việt
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
            "status_slug" => $status, // dùng để lọc trong js
            "image_url" => $row["image_url"],
            "status_label" => $status_label, 
        ];
        array_push($events_data, $event);
    }
}

$conn->close();

// 4. TRẢ VỀ KẾT QUẢ JSON
echo json_encode($events_data, JSON_UNESCAPED_UNICODE);
?>