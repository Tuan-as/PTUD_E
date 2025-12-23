<?php include 'fetch/Admin_GetData.php'; ?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="../css/Admin.css">

<div class="container-fluid">
  <div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <!-- MAIN CONTENT -->
    <div class="col-12 col-lg-10 p-4 bg-light fade-up">
      <!-- MOBILE MENU BUTTON -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-dark d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
            <span class="fw-bold">&#9776; Menu</span>
          </button>
        </div>
      </div>
      <!-- PAGE TITLE -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Dashboard</h2>
      </div>

      <!-- KPI CARDS -->
      <div class="row g-4 mb-5">
        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Đơn hàng</small>
            <h4 class="fw-bold mb-0"><?php echo $totalOrders; ?></h4>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Doanh thu</small>
            <h4 class="fw-bold mb-0">
              <?php echo number_format($totalRevenue); ?> đ
            </h4>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Khách hàng</small>
            <h4 class="fw-bold mb-0"><?php echo $totalUsers; ?></h4>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Sản phẩm</small>
            <h4 class="fw-bold mb-0"><?php echo $totalProducts; ?></h4>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Đơn chờ xử lý</small>
            <h4 class="fw-bold mb-0"><?php echo $orderwaitlist; ?></h4>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 rounded-0 p-3 text-center">
            <small class="text-muted">Đơn bị hủy</small>
            <h4 class="fw-bold mb-0"><?php echo $numcanceledorder; ?></h4>
          </div>
        </div>
      </div>

      <!-- CHART + NOTIFICATION -->
      <div class="row g-4 mb-5">

        <!-- CHART PLACEHOLDER -->
        <div class="col-lg-8">
          <div class="card border-0 rounded-0 p-4 h-100">
            <div class="col-lg-8">
              <div class="card border-0 rounded-0 p-4 h-100">
                <h5 class="fw-bold mb-3">Doanh thu theo thời gian</h5>
                <canvas id="revenueChart" height="120"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- ALERT / NOTIFICATION -->
        <div class="col-lg-4">
          <div class="card border-0 rounded-0 p-4 h-100">
            <h5 class="fw-bold mb-3">Thông báo nhanh</h5>

            <ul class="list-group list-group-flush">
              <li class="list-group-item px-0 small">
                Đơn hàng mới vừa được tạo 
              </li>
              <li class="list-group-item px-0 small">
                3 sản phẩm sắp hết hàng
              </li>
              <li class="list-group-item px-0 small">
                1 đơn hàng vừa bị hủy
              </li>
              <li class="list-group-item px-0 small">
                Khách hàng mới đăng ký
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- RECENT ORDERS TABLE -->
      <div class="card border-0 rounded-0 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0">Đơn hàng gần đây</h5>
          <a href="Admin_DonHang.php" class="btn btn-outline-dark btn-sm">
            Xem chi tiết
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="bg-light">
              <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $o): ?>
              <tr>
                <td><?php echo $o['code']; ?></td>
                <td><?php echo $o['customer']; ?></td>
                <td><?php echo number_format($o['total']); ?> đ</td>
                <td><?php echo $o['status']; ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
  const revenueLabels = <?= json_encode(array_column($revenueChart, 'ngay')) ?>;
  const revenueData   = <?= json_encode(array_column($revenueChart, 'doanhthu')) ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/Admin_Dashboard.js"></script>
