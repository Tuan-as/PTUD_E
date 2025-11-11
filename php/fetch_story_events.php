<?php
/*
 * CHỨC NĂNG: Lấy 6 Sự kiện mới nhất (theo ngày bắt đầu)
 * SỬ DỤNG CHO: Trang Câu Chuyện (để hiển thị ở slider Sự Kiện)
 */

// Thiết lập header để trả về kiểu JSON (UTF-8)
header('Content-Type: application/json; charset=utf-8');

// --- 1. THIẾT LẬP KẾT NỐI DATABASE ---
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "CauChuyen_db"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
// Đảm bảo hỗ trợ UTF-8
$conn->set_charset("utf8mb4");

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die(json_encode(["error" => "Lỗi kết nối MySQL: " . $conn->connect_error]));
}

// --- 2. HÀM HỖ TRỢ: TÍNH TRẠNG THÁI SỰ KIỆN ---
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

// --- 3. TRUY VẤN DỮ LIỆU ---
// Lấy 6 sự kiện mới nhất (sắp xếp theo ngày bắt đầu giảm dần)
$sql = "SELECT id, title, description, event_date_range, location, start_date, end_date, image_url 
        FROM events 
        ORDER BY start_date DESC 
        LIMIT 6";

$result = $conn->query($sql);
$events_data = []; // Mảng chứa kết quả

// --- 4. XỬ LÝ KẾT QUẢ ---
if ($result && $result->num_rows > 0) {
    // Lặp qua từng dòng kết quả
    while($row = $result->fetch_assoc()) {
        
        // Gọi hàm hỗ trợ để lấy trạng thái (past, current, upcoming)
        $status = calculateStatus($row['start_date'], $row['end_date']);
        
        // Định dạng lại dữ liệu cho JSON
        $event = [
            "id" => $row["id"], 
            "title" => $row["title"],
            "description" => $row["description"],
            "date_range" => $row["event_date_range"],
            "location" => $row["location"],
            "image_url" => $row["image_url"],
            
            // Tạo nhãn Tiếng Việt dựa trên trạng thái
            "status_label" => ($status == 'current' ? 'Đang diễn ra' : ($status == 'upcoming' ? 'Sắp diễn ra' : 'Đã diễn ra')),
            
            // Gửi kèm slug (tên trạng thái) để JS/CSS có thể sử dụng
            "status_slug" => $status
        ];
        // Thêm sự kiện vào mảng kết quả
        array_push($events_data, $event);
    }
} 

// --- 5. ĐÓNG KẾT NỐI VÀ TRẢ VỀ JSON ---
$conn->close();
echo json_encode($events_data, JSON_UNESCAPED_UNICODE);
?>