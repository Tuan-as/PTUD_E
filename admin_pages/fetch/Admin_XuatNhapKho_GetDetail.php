<?php
include '../fetch/Admin_GetData.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT l.*, s.Name as ProductName, s.TonKho as CurrentStock, CONCAT(nd.Ho,' ',nd.Ten) as StaffName
    FROM LichSuThayDoiTonKho l
    JOIN SKU s ON l.MaSKU = s.MaSKU
    LEFT JOIN NguoiDung nd ON l.NguoiThucHien = nd.MaNguoiDung
    WHERE l.MaThayDoiTonKho = ?
");
$stmt->execute([$id]);
$log = $stmt->fetch();

if (!$log) { echo "Không tìm thấy dữ liệu"; exit; }
?>

<div class="row g-4">
    <div class="col-md-6 border-end">
        <h6 class="text-muted small text-uppercase fw-bold mb-3">Thông tin phiếu</h6>
        <p class="mb-2"><strong>Mã phiếu:</strong> #<?= $log['MaThayDoiTonKho'] ?></p>
        <p class="mb-2"><strong>Loại:</strong> 
            <span class="badge <?= $log['LoaiThayDoi']=='Nhập kho'?'bg-success':'bg-dark' ?>">
                <?= $log['LoaiThayDoi'] ?>
            </span>
        </p>
        <p class="mb-2"><strong>Ngày thực hiện:</strong> <?= date('d/m/Y H:i:s', strtotime($log['NgayThucHien'])) ?></p>
        <p class="mb-0"><strong>Người thực hiện:</strong> <?= $log['StaffName'] ?></p>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted small text-uppercase fw-bold mb-3">Thông tin sản phẩm</h6>
        <p class="mb-2"><strong>Sản phẩm:</strong> <?= htmlspecialchars($log['ProductName']) ?></p>
        <p class="mb-2"><strong>Mã SKU:</strong> <?= $log['MaSKU'] ?></p>
        <p class="mb-2"><strong>Số lượng biến động:</strong> <span class="fs-5 fw-bold text-primary"><?= $log['SoLuong'] ?></span></p>
        <p class="mb-0"><strong>Tồn kho hiện tại:</strong> <?= $log['CurrentStock'] ?></p>
    </div>
    <div class="col-12 mt-3">
        <div class="bg-light p-3 border">
            <h6 class="text-muted small text-uppercase fw-bold mb-2">Ghi chú</h6>
            <p class="mb-0 italic"><?= nl2br(htmlspecialchars($log['GhiChu'] ?: 'Không có ghi chú.')) ?></p>
        </div>
    </div>
</div>