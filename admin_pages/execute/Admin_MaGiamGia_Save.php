<?php
include '../fetch/Admin_GetData.php'; 
header('Content-Type: application/json');

session_start();
// Lấy MaNguoiDung của người đang đăng nhập (Admin)
$nguoiTao = $_SESSION['admin_id'] ?? 1;

$id = $_POST['MaGiamGia'] ?? '';
$code = $_POST['CodeGiamGia'];
$loai = $_POST['LoaiGiamGia'];
$muc = $_POST['MucGiamGia'];
$min = $_POST['GiaTriDonHangToiThieu'] ?: 0;
$max = $_POST['SoLanSuDungToiDa'] ?: 999;

// Xử lý ngày bắt đầu: Nếu không điền thì lấy NOW()
$start = !empty($_POST['NgayBatDau']) ? $_POST['NgayBatDau'] : date('Y-m-d H:i:s');
// Xử lý ngày hết hạn: Nếu không điền thì để null
$end = !empty($_POST['NgayHetHan']) ? $_POST['NgayHetHan'] : null;

$status = $_POST['TrangThai'];

try {
    if ($id) {
        // Cập nhật mã đã có
        $sql = "UPDATE MaGiamGia SET 
                CodeGiamGia = ?, 
                LoaiGiamGia = ?, 
                MucGiamGia = ?, 
                GiaTriDonHangToiThieu = ?, 
                SoLanSuDungToiDa = ?, 
                NgayBatDau = ?, 
                NgayHetHan = ?, 
                TrangThai = ? 
                WHERE MaGiamGia = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code, $loai, $muc, $min, $max, $start, $end, $status, $id]);
    } else {
        // Thêm mới: Sử dụng NOW() cho các cột thời gian nếu cần hoặc gán giá trị biến $start
        $sql = "INSERT INTO MaGiamGia (
                    CodeGiamGia, LoaiGiamGia, MucGiamGia, 
                    GiaTriDonHangToiThieu, SoLanSuDungToiDa, 
                    NgayBatDau, NgayHetHan, NguoiTao, TrangThai
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        // Ở đây $start đã được xử lý là NOW() nếu input trống
        $stmt->execute([$code, $loai, $muc, $min, $max, $start, $end, $nguoiTao, $status]);
    }
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}