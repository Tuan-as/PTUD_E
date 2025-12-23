<?php
// db.php ở root, file này ở store_pages/fetchdata -> lùi 2 cấp
require '../../db.php';

// 1. Lấy danh sách từ bảng CATEGORIES (chứ không phải bảng awards)
$sql = "SELECT id, name, slug FROM award_categories ORDER BY id ASC";
$result = $conn->query($sql);

$awards = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        
        // 2. Đếm số ảnh thuộc category này
        // Logic: Đếm ảnh trong bảng award_images, 
        // nhưng phải JOIN sang bảng awards để check category_id
        $countSql = "
            SELECT COUNT(*) AS total 
            FROM award_images ai
            JOIN awards a ON ai.award_id = a.id
            WHERE a.category_id = " . $row['id'];
            
        $countRes = $conn->query($countSql);
        $count = ($countRes) ? $countRes->fetch_assoc()['total'] : 0;

        $awards[] = [
            "id" => $row["id"], // Thêm ID để dễ xử lý nếu cần
            "name" => $row["name"],
            "slug" => $row["slug"],
            "count" => $count
        ];
    }
}

echo json_encode($awards);
?>