<?php 
include 'fetch/Admin_GetData.php'; 
include 'includes/header.php'; 
?>
<link rel="stylesheet" href="../css/Admin.css">
<div class="container-fluid">
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
      <div class="mb-4">
        <h2 class="fw-bold">Quản lý khách hàng</h2>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-4">
        <h5 class="fw-bold mb-3">Tìm kiếm & lọc khách hàng</h5>
        <form class="row g-3 align-items-end" method="GET">
          <div class="col-lg-3">
            <label class="form-label small">Tên khách hàng</label>
            <input type="text" name="search_name" class="form-control" placeholder="Nhập tên..." value="<?= htmlspecialchars($searchCustName) ?>">
          </div>
          <div class="col-lg-3">
            <label class="form-label small">Đăng ký từ ngày</label>
            <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($filterFromDate) ?>">
          </div>
          <div class="col-lg-2">
            <label class="form-label small">Mức độ mua hàng</label>
            <select name="buy_level" class="form-select">
              <option value="">Tất cả</option>
              <option value="mua_nhieu" <?= $filterBuyLevel == 'mua_nhieu' ? 'selected' : '' ?>>Mua nhiều</option>
              <option value="mua_vua" <?= $filterBuyLevel == 'mua_vua' ? 'selected' : '' ?>>Mua vừa</option>
              <option value="mua_it" <?= $filterBuyLevel == 'mua_it' ? 'selected' : '' ?>>Mua ít</option>
            </select>
          </div>
          <div class="col-lg-2">
            <button type="submit" class="btn btn-dark w-100">Lọc dữ liệu</button>
          </div>
          <div class="col-lg-2">
            <a href="Admin_KhachHang.php" class="btn btn-outline-secondary w-100">Xóa lọc</a>
          </div>
        </form>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-5">
        <h5 class="fw-bold mb-3">Danh sách khách hàng</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="bg-light text-center">
              <tr>
                <th>Mã KH</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Ngày đăng ký</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $c): ?>
              <tr>
                <td class="text-center fw-bold"><?php echo $c['id']; ?></td>
                <td><?php echo htmlspecialchars($c['name']); ?></td>
                <td><?php echo htmlspecialchars($c['Email']); ?></td>
                <td><?php echo htmlspecialchars($c['phone']); ?></td>
                <td class="text-center"><?php echo date('d/m/Y', strtotime($c['register_date'])); ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-dark" 
                          onclick='showCustomerDetail(<?= json_encode($c, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                    Xem chi tiết
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="adminModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody"></div>
    </div>
  </div>
</div>

<script src="../js/Admin_KhachHang.js"></script>