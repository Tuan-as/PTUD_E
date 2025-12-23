<?php
header('Content-Type: application/json');
// Kiểm tra kỹ đường dẫn db.php. Nếu file này nằm trong thư mục fetch/, 
// và db.php nằm trong includes/, thì phải là ../../includes/db.php hoặc ../includes/db.php tùy cấu trúc root.
include '../includes/db.php';

$maThuocTinh = intval($_GET['thuocTinh'] ?? 0);

if(!$maThuocTinh){
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT MaGiaTri, GiaTri AS TenGiaTri FROM GiaTriThuocTinh WHERE MaThuocTinh = ? ORDER BY GiaTri ASC");
    $stmt->execute([$maThuocTinh]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    echo json_encode($data);
} catch (Exception $e) {
    // Trả về lỗi định dạng JSON để JS không bị crash
    echo json_encode(["error" => $e->getMessage()]);
}
?>