<?php
// Chỉ start session nếu chưa active
if(session_status() == PHP_SESSION_NONE) session_start();


include '../fetch/Admin_GetData.php';

// Phần còn lại giữ nguyên
$MaDanhMuc = intval($_POST['MaDanhMuc'] ?? 0);
$TenDanhMuc = trim($_POST['TenDanhMuc'] ?? '');
$MoTa = trim($_POST['MoTa'] ?? '');
$NguoiChinhSuaCuoi = $_SESSION['admin_id'] ?? 1;
$NgayChinhSua = date('Y-m-d H:i:s');

if(!$TenDanhMuc){
    echo json_encode(['success'=>false,'message'=>'Tên danh mục không được để trống']);
    exit;
}

try {
    if($MaDanhMuc){ 
        $stmt = $pdo->prepare("
            UPDATE DanhMucSanPham
            SET TenDanhMuc=?, MoTa=?, NguoiChinhSuaCuoi=?, NgayChinhSua=?
            WHERE MaDanhMuc=?
        ");
        $stmt->execute([$TenDanhMuc,$MoTa,$NguoiChinhSuaCuoi,$NgayChinhSua,$MaDanhMuc]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO DanhMucSanPham (TenDanhMuc, MoTa, NguoiChinhSuaCuoi, NgayChinhSua)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$TenDanhMuc,$MoTa,$NguoiChinhSuaCuoi,$NgayChinhSua]);
    }
    echo json_encode(['success'=>true]);
} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
