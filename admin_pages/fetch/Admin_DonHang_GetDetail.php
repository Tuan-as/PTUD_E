<?php
include '../includes/db.php';
$id = $_GET['id'] ?? 0;

// 1. Lấy thông tin đơn hàng và khách hàng
// Kết nối bảng DonHang với NguoiDung để lấy tên khách hàng
// Kết nối với DiaChiNhanHang để lấy địa chỉ giao cụ thể
$stmt = $pdo->prepare("SELECT dh.*, kh.Ho, kh.Ten, kh.Email, kh.SDT, dc.DiaChiNhanHang 
                       FROM donhang dh 
                       JOIN nguoidung kh ON dh.MaNguoiDung = kh.MaNguoiDung 
                       LEFT JOIN diachinhanhang dc ON dh.MaDiaChiNhanHang = dc.MaDiaChiNhanHang
                       WHERE dh.MaDonHang = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) exit('<p class="p-3 text-center">Dữ liệu đơn hàng không tồn tại.</p>');

// 2. Lấy chi tiết sản phẩm
// Trong DB của bạn: bảng là `sku`, cột tên là `Name`
$stmtItem = $pdo->prepare("SELECT ct.*, s.Name, s.SKUCode 
                           FROM ctdonhang ct 
                           JOIN sku s ON ct.MaSKU = s.MaSKU 
                           WHERE ct.MaDonHang = ?");
$stmtItem->execute([$id]);
$items = $stmtItem->fetchAll();
?>

<div class="row g-4">
    <div class="col-md-6">
        <h6 class="fw-bold text-uppercase border-bottom pb-2 small" style="color: #333;">Khách hàng</h6>
        <div class="lh-sm small">
            <p class="mb-1">Họ tên: <strong><?= htmlspecialchars($order['Ho'] . ' ' . $order['Ten']) ?></strong></p>
            <p class="mb-1">Email: <?= htmlspecialchars($order['Email']) ?></p>
            <p class="mb-1">SĐT: <?= htmlspecialchars($order['SDT']) ?></p>
            <p class="mb-0 text-muted">Địa chỉ: <?= htmlspecialchars($order['DiaChiNhanHang'] ?? 'Chưa cập nhật') ?></p>
        </div>
    </div>

    <div class="col-md-6">
        <h6 class="fw-bold text-uppercase border-bottom pb-2 small" style="color: #333;">Thông tin đơn hàng</h6>
        <div class="lh-sm small">
            <p class="mb-1">Mã đơn: <strong>#<?= $order['MaDonHang'] ?></strong></p>
            <p class="mb-1">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['NgayDat'])) ?></p>
            <p class="mb-0">Trạng thái: <span class="badge bg-dark rounded-0 text-uppercase"><?= $order['TrangThai'] ?></span></p>
        </div>
    </div>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-sm align-middle border mb-0">
                <thead class="bg-light small text-uppercase fw-bold">
                    <tr>
                        <th class="p-2">Sản phẩm</th>
                        <th class="text-center">Mã SKU</th>
                        <th class="text-center">SL</th>
                        <th class="text-end p-2">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td class="p-2">
                            <div class="fw-bold"><?= htmlspecialchars($item['Name']) ?></div>
                        </td>
                        <td class="text-center text-muted"><?= htmlspecialchars($item['SKUCode']) ?></td>
                        <td class="text-center"><?= $item['SoLuong'] ?></td>
                        <td class="text-end p-2"><?= number_format($item['DonGia'] * $item['SoLuong'], 0, ',', '.') ?> đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="border-top">
                    <tr class="fw-bold" style="background-color: #f8f9fa;">
                        <td colspan="3" class="text-end p-2 text-uppercase small">Tổng thanh toán:</td>
                        <td class="text-end p-2" style="font-size: 1.1rem;"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-12 mt-3 pt-3 border-top">
        <div class="d-flex gap-2">
            <?php if ($order['TrangThai'] == 'PENDING'): ?>
                <button class="btn btn-dark btn-sm rounded-0 px-4 fw-bold" onclick="updateOrderStatus(<?= $id ?>, 'CONFIRMED')">XÁC NHẬN ĐƠN</button>
                <button class="btn btn-outline-danger btn-sm rounded-0 fw-bold" onclick="updateOrderStatus(<?= $id ?>, 'CANCELLED')">HỦY ĐƠN</button>
            <?php elseif ($order['TrangThai'] == 'CONFIRMED'): ?>
                <button class="btn btn-primary btn-sm rounded-0 px-4 fw-bold" onclick="updateOrderStatus(<?= $id ?>, 'SHIPPING')">GIAO HÀNG</button>
            <?php else: ?>
                <div class="w-100 bg-light p-2 text-center small fw-bold text-uppercase border text-muted">
                    Đơn hàng này đang ở trạng thái: <?= $order['TrangThai'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>