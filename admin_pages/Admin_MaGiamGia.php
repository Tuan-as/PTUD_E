<?php 
include 'fetch/Admin_GetData.php'; 
include 'includes/header.php'; 


// Lấy tham số lọc
$searchCode = $_GET['search_code'] ?? '';
$filterStatus = $_GET['filter_status'] ?? '';
$filterType = $_GET['filter_type'] ?? '';

// Xây dựng câu SQL lọc
$sql = "SELECT mg.*, 
        (SELECT COUNT(*) FROM LichSuDungMaGiamGia ls WHERE ls.MaGiamGia = mg.MaGiamGia) as usage_count
        FROM MaGiamGia mg WHERE 1=1";
$params = [];

if (!empty($searchCode)) {
    $sql .= " AND mg.CodeGiamGia LIKE ?";
    $params[] = '%' . $searchCode . '%';
}
if (!empty($filterStatus)) {
    $sql .= " AND mg.TrangThai = ?";
    $params[] = $filterStatus;
}
if (!empty($filterType)) {
    $sql .= " AND mg.LoaiGiamGia = ?";
    $params[] = $filterType;
}

$sql .= " ORDER BY mg.MaGiamGia DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$promotions = $stmt->fetchAll();
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
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Quản lý mã giảm giá</h2>
        <button class="btn btn-dark" onclick="openPromoForm()">+ Thêm mã mới</button>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-4">
        <form class="row g-3 align-items-end" method="GET">
          <div class="col-lg-4">
            <label class="form-label small">Mã giảm giá</label>
            <input type="text" name="search_code" class="form-control" placeholder="Tìm mã..." value="<?= htmlspecialchars($searchCode) ?>">
          </div>

          <div class="col-lg-3">
            <label class="form-label small">Trạng thái</label>
            <select name="filter_status" class="form-select">
              <option value="">Tất cả</option>
              <option value="ACTIVE" <?= $filterStatus == 'ACTIVE' ? 'selected' : '' ?>>Đang hoạt động</option>
              <option value="INACTIVE" <?= $filterStatus == 'INACTIVE' ? 'selected' : '' ?>>Không hoạt động</option>
            </select>
          </div>

          <div class="col-lg-3">
            <label class="form-label small">Loại giảm giá</label>
            <select name="filter_type" class="form-select">
              <option value="">Tất cả</option>
              <option value="PERCENT" <?= $filterType == 'PERCENT' ? 'selected' : '' ?>>Phần trăm (%)</option>
              <option value="FIXED" <?= $filterType == 'FIXED' ? 'selected' : '' ?>>Số tiền cố định</option>
            </select>
          </div>

          <div class="col-lg-2">
            <button type="submit" class="btn btn-dark w-100">Lọc</button>
          </div>
        </form>
      </div>

      <div class="card border-0 rounded-0 p-4 mb-5">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="bg-light text-center">
              <tr>
                <th>Code</th>
                <th>Loại</th>
                <th>Mức giảm</th>
                <th>Thời gian</th>
                <th>Lượt dùng</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($promotions as $p): ?>
              <tr>
                <td class="fw-bold text-center"><?= $p['CodeGiamGia'] ?></td>
                <td class="text-center"><?= $p['LoaiGiamGia'] == 'PERCENT' ? 'Phần trăm' : 'Cố định' ?></td>
                <td class="text-center">
                    <?= number_format($p['MucGiamGia']) ?><?= $p['LoaiGiamGia'] == 'PERCENT' ? '%' : 'đ' ?>
                </td>
                <td class="small text-center">
                  Từ: <?= date('d/m/Y', strtotime($p['NgayBatDau'])) ?><br>
                  Đến: <?= $p['NgayHetHan'] ? date('d/m/Y', strtotime($p['NgayHetHan'])) : 'Vô thời hạn' ?>
                </td>
                <td class="text-center"><?= $p['usage_count'] ?> / <?= $p['SoLanSuDungToiDa'] ?></td>
                <td class="text-center" id="status-badge-<?= $p['MaGiamGia'] ?>">
                  <span class="badge <?= $p['TrangThai'] == 'ACTIVE' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= $p['TrangThai'] ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-dark" onclick="openPromoForm(<?= $p['MaGiamGia'] ?>)">Sửa</button>
                    <button class="btn btn-outline-dark" onclick="togglePromo(<?= $p['MaGiamGia'] ?>)">Bật/Tắt</button>
                    <button class="btn btn-outline-danger" onclick="deletePromo(<?= $p['MaGiamGia'] ?>)">Xóa</button>
                  </div>
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
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Mã giảm giá</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody"></div>
    </div>
  </div>
</div>

<script src="../js/Admin_MaGiamGia.js"></script>