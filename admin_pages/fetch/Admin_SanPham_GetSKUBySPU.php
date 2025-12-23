<?php
header('Content-Type: application/json');

if (!isset($_GET['spu_id'])) {
    echo json_encode([]);
    exit;
}

$spuId = (int)$_GET['spu_id'];

// Kết nối DB
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=locknlock_store;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    echo json_encode([]);
    exit;
}

// Lấy danh sách SKU theo SPU
$stmt = $pdo->prepare("
    SELECT 
        MaSKU,
        MaSPU,
        SKUCode,
        Name as NameSKU,
        GiaGoc,
        GiaGiam,
        TonKho,
        TrangThai
    FROM SKU
    WHERE MaSPU = :spu_id
    ORDER BY MaSKU ASC
");
$stmt->execute(['spu_id' => $spuId]);
$skus = $stmt->fetchAll();

echo json_encode($skus);
