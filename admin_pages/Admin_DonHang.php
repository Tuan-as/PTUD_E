<?php 
include 'includes/db.php';
include 'fetch/Admin_GetData.php'; 
include 'includes/header.php'; 
?>
<link rel="stylesheet" href="../css/Admin.css">
<div class="container-fluid bg-white">
  <div class="row">
    <?php include 'includes/sidebar.php'; ?>

    <div class="col-12 col-lg-10 p-4 bg-light fade-up">
      <!-- MOBILE MENU BUTTON -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-dark d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
            <span class="fw-bold">&#9776; Menu</span>
          </button>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-uppercase mb-0">Quản lý đơn hàng</h2>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-4 shadow-sm bg-white">
        <form class="row g-3" method="get">
          <div class="col-md-2">
            <label class="form-label small fw-bold text-uppercase">Mã đơn</label>
            <input type="text" name="search_code" class="form-control rounded-0" value="<?= htmlspecialchars($searchCode ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-uppercase">Khách hàng</label>
            <input type="text" name="search_customer" class="form-control rounded-0" value="<?= htmlspecialchars($searchCustomer ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-uppercase">Trạng thái</label>
            <select class="form-select rounded-0" name="filter_status">
              <option value="">Tất cả trạng thái</option>
              <?php 
                $statuses = ['PENDING', 'CONFIRMED', 'SHIPPING', 'COMPLETED', 'CANCELLED', 'RETURNED/REFUNDED'];
                foreach($statuses as $st): 
              ?>
                <option value="<?= $st ?>" <?= (($filterStatus ?? '') == $st) ? 'selected' : '' ?>><?= $st ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
             <label class="form-label small fw-bold text-uppercase">Khoảng ngày</label>
             <div class="input-group">
                <input type="date" name="from_date" class="form-control rounded-0" value="<?= $fromDate ?? '' ?>">
                <input type="date" name="to_date" class="form-control rounded-0" value="<?= $toDate ?? '' ?>">
             </div>
          </div>
          <div class="col-12 text-end">
            <button class="btn btn-dark btn-sm rounded-0 px-4 fw-bold" type="submit">LỌC ĐƠN HÀNG</button>
            <a href="Admin_DonHang.php" class="btn btn-outline-dark btn-sm rounded-0">XÓA LỌC</a>
          </div>
        </form>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-5 shadow-sm bg-white">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 border">
            <thead class="bg-light text-center small text-uppercase fw-bold">
              <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody class="text-center">
              <?php if (!empty($listOrders)): ?>
                <?php foreach ($listOrders as $o): ?>
                <tr>
                  <td class="fw-bold">#<?= $o['MaDonHang'] ?></td>
                  <td class="text-start"><?= htmlspecialchars($o['customer_name']) ?></td>
                  <td><?= date('d/m/Y', strtotime($o['NgayDat'])) ?></td>
                  <td class="fw-bold"><?= number_format($o['TongTien'], 0, ',', '.') ?> đ</td>
                  <td><span class="small fw-bold text-uppercase"><?= $o['TrangThai'] ?></span></td>
                  <td>
                    <button class="btn btn-outline-dark btn-sm rounded-0 px-3" onclick="openOrderDetail(<?= $o['MaDonHang'] ?>)">
                      Chi tiết
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="p-4 text-muted">Không tìm thấy đơn hàng nào.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg border-0">
    <div class="modal-content rounded-0 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-uppercase">Chi tiết đơn hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="orderModalBody">
          </div>
    </div>
  </div>
</div>

<script src="../js/Admin_DonHang.js"></script>

<?php include 'includes/footer.php'; ?>