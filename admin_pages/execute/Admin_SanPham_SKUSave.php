<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
    exit;
}

$spuId = intval($_POST['MaSPU'] ?? 0);
$skuId = intval($_POST['MaSKU'] ?? 0);
$code = trim($_POST['SKUCode'] ?? '');
$name = trim($_POST['Name'] ?? '');
$giaGoc = floatval($_POST['GiaGoc'] ?? 0);
$giaGiam = floatval($_POST['GiaGiam'] ?? 0);
$tonKho = intval($_POST['TonKho'] ?? 0);
$trangThai = $_POST['TrangThai'] ?? 'ACTIVE';
$maGiaTri = intval($_POST['GiaTri'] ?? 0); // Lấy từ name="GiaTri" trong form

$nguoiTao = $_SESSION['admin_id'] ?? 1;
$ngayTao = date('Y-m-d H:i:s');

try{
    $pdo->beginTransaction();

    if($skuId > 0){
        $stmt = $pdo->prepare("UPDATE SKU SET SKUCode=:code, Name=:name, GiaGoc=:giaGoc, GiaGiam=:giaGiam, TonKho=:tonKho, TrangThai=:trangThai, NguoiChinhSuaCuoi=:nguoiTao WHERE MaSKU=:skuId");
        $stmt->execute(compact('code','name','giaGoc','giaGiam','tonKho','trangThai','nguoiTao','skuId'));
        
        $pdo->prepare("DELETE FROM ThuocTinhSanPham WHERE MaSKU=?")->execute([$skuId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO SKU (MaSPU, SKUCode, Name, GiaGoc, GiaGiam, TonKho, TrangThai, NguoiTao, NguoiChinhSuaCuoi, NgayTao) VALUES (:spuId, :code, :name, :giaGoc, :giaGiam, :tonKho, :trangThai, :nguoiTao, :nguoiTao, :ngayTao)");
        $stmt->execute(compact('spuId','code','name','giaGoc','giaGiam','tonKho','trangThai','nguoiTao','ngayTao'));
        $skuId = $pdo->lastInsertId();
    }

    if($maGiaTri > 0){
        $stmt2 = $pdo->prepare("INSERT INTO ThuocTinhSanPham (MaSKU, MaGiaTri) VALUES (?, ?)");
        $stmt2->execute([$skuId, $maGiaTri]);
    }

    $pdo->commit();
    echo json_encode(['status'=>'success']);
} catch(PDOException $e){
    $pdo->rollBack();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}