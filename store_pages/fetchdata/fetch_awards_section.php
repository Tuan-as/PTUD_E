<?php
// db.php ở root, file này ở store_pages/fetchdata -> lùi 2 cấp
require '../../db.php'; 

// Kiểm tra có slug không để tránh lỗi
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    
    // Sửa câu truy vấn: JOIN 3 bảng
    // award_images -> awards -> award_categories (nơi chứa slug)
    $sql = "
        SELECT ai.image_path, ai.alt_text, ac.name
        FROM award_images ai
        JOIN awards a ON ai.award_id = a.id
        JOIN award_categories ac ON a.category_id = ac.id
        WHERE ac.slug = '$slug'
    ";

    $res = $conn->query($sql);
    $data = [];
    
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
}
?>