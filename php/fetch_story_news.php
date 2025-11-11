<?php
/*
 * CHỨC NĂNG: Lấy 6 bài Tin Tức mới nhất
 * SỬ DỤNG CHO: Trang Câu Chuyện (để hiển thị ở slider Tin Tức)
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

// --- 2. TRUY VẤN DỮ LIỆU ---
// Lấy 6 bài viết mới nhất, sắp xếp theo ngày giảm dần
$sql = "SELECT id, title, description, DATE_FORMAT(post_date, '%d/%m/%Y') AS date, category, image_url 
        FROM articles 
        ORDER BY post_date DESC 
        LIMIT 6";

$result = $conn->query($sql);
$articles_data = []; // Mảng chứa kết quả

// --- 3. XỬ LÝ KẾT QUẢ ---
if ($result && $result->num_rows > 0) {
    // Lặp qua từng dòng kết quả
    while($row = $result->fetch_assoc()) {
        // Định dạng lại dữ liệu cho JSON
        $article = [
            "id" => $row["id"], 
            // Chuyển 'product' -> 'Sản phẩm', 'company' -> 'Công ty'
            "tag" => ($row["category"] == "product" ? "Sản phẩm" : "Công ty"), 
            "category_slug" => $row["category"],
            "title" => $row["title"],
            "description" => $row["description"],
            "date" => $row["date"], // Ngày đã được format bởi SQL
            "imageUrl" => $row["image_url"]
        ];
        // Thêm bài viết vào mảng kết quả
        array_push($articles_data, $article);
    }
} 

// --- 4. ĐÓNG KẾT NỐI VÀ TRẢ VỀ JSON ---
$conn->close();
echo json_encode($articles_data, JSON_UNESCAPED_UNICODE);
?>