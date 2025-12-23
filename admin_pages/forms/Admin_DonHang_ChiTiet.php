<?php
include '../fetch/Admin_GetData.php';

$id = $_GET['id'];

$order = $pdo->prepare("
SELECT dh.*, CONCAT(nd.Ho,' ',nd.Ten) AS KhachHang
FROM DonHang dh
JOIN NguoiDung nd ON dh.MaNguoiDung = nd.MaNguoiDung
WHERE dh.MaDonHang=?
");
$order->execute([$id]);
$o = $order->fetch();
?>

<h6>Khách hàng: <?= $o['KhachHang'] ?></h6>
<p>Trạng thái: <?= $o['TrangThai'] ?></p>

<div class="mt-3">
<?php if ($o['TrangThai']=='PENDING'): ?>
<button class="btn btn-dark btn-sm"
 onclick="updateOrderStatus(<?= $id ?>,'CONFIRMED')">Xác nhận</button>
<button class="btn btn-danger btn-sm"
 onclick="updateOrderStatus(<?= $id ?>,'CANCELLED')">Huỷ</button>

<?php elseif (in_array($o['TrangThai'],['CONFIRMED','SHIPPING'])): ?>
<span class="badge bg-warning">Đang giao</span>

<?php elseif ($o['TrangThai']=='COMPLETED'): ?>
<span class="badge bg-success">Hoàn thành</span>

<?php elseif ($o['TrangThai']=='CANCELLED'): ?>
<span class="badge bg-danger">Huỷ bỏ</span>

<?php else: ?>
<span class="badge bg-secondary">Đã đổi trả</span>
<?php endif; ?>
</div>
