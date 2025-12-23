<?php 
include 'fetch/Admin_GetData.php'; 
include 'includes/header.php'; 

// --- LOGIC LỌC DỮ LIỆU ---
$searchProduct = $_GET['search_product'] ?? '';
$filterType = $_GET['filter_type'] ?? '';
$fromDate = $_GET['from_date'] ?? '';

$sql = "SELECT l.MaThayDoiTonKho as code, l.NgayThucHien as date, l.LoaiThayDoi as type, 
               s.Name as product, l.SoLuong as quantity, l.GhiChu as note, 
               CONCAT(nd.Ho,' ',nd.Ten) as staff
        FROM LichSuThayDoiTonKho l
        JOIN SKU s ON l.MaSKU = s.MaSKU
        LEFT JOIN NguoiDung nd ON l.NguoiThucHien = nd.MaNguoiDung
        WHERE 1=1";

$params = [];
if (!empty($searchProduct)) {
    $sql .= " AND (s.Name LIKE ? OR l.MaThayDoiTonKho LIKE ?)";
    $params[] = "%$searchProduct%"; $params[] = "%$searchProduct%";
}
if (!empty($filterType) && $filterType != 'Tất cả') {
    $sql .= " AND l.LoaiThayDoi = ?";
    $params[] = $filterType;
}
if (!empty($fromDate)) {
    $sql .= " AND l.NgayThucHien >= ?";
    $params[] = $fromDate . ' 00:00:00';
}
$sql .= " ORDER BY l.NgayThucHien DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inventoryLogs = $stmt->fetchAll();
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
      <h2 class="fw-bold text-uppercase mb-4">Quản lý xuất / nhập kho</h2>

      <div class="card border-0 rounded-0 p-4 mb-4 shadow-sm bg-white">
        <form class="row g-3 align-items-end" method="GET">
          <div class="col-md-4">
            <label class="form-label small fw-bold text-uppercase">Sản phẩm / Mã phiếu</label>
            <input type="text" name="search_product" class="form-control rounded-0" value="<?= htmlspecialchars($searchProduct) ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-uppercase">Loại giao dịch</label>
            <select name="filter_type" class="form-select rounded-0">
              <option value="Tất cả">Tất cả</option>
              <option value="Nhập kho" <?= $filterType == 'Nhập kho'?'selected':'' ?>>Nhập kho</option>
              <option value="Xuất kho" <?= $filterType == 'Xuất kho'?'selected':'' ?>>Xuất kho</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-uppercase">Từ ngày</label>
            <input type="date" name="from_date" class="form-control rounded-0" value="<?= $fromDate ?>">
          </div>
          <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm rounded-0 w-100 fw-bold">LỌC</button>
            <button type="button" class="btn btn-outline-dark btn-sm rounded-0 w-100 fw-bold" onclick="openInventoryForm()">+ TẠO PHIẾU</button>
          </div>
        </form>
      </div>

      <div class="card border-0 rounded-0 p-4 shadow-sm bg-white">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 border">
            <thead class="bg-light text-center small text-uppercase fw-bold">
              <tr>
                <th>Mã phiếu</th>
                <th>Ngày</th>
                <th>Loại</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Người thực hiện</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody class="text-center">
              <?php foreach ($inventoryLogs as $log): ?>
              <tr>
                <td class="fw-bold">#<?= $log['code'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($log['date'])) ?></td>
                <td>
                    <span class="badge rounded-0 <?= $log['type']=='Nhập kho'?'bg-success':'bg-dark' ?>">
                        <?= strtoupper($log['type']) ?>
                    </span>
                </td>
                <td class="text-start"><?= htmlspecialchars($log['product']) ?></td>
                <td class="fw-bold"><?= $log['quantity'] ?></td>
                <td><?= $log['staff'] ?></td>
                <td>
                  <button class="btn btn-outline-dark btn-sm rounded-0 px-3" onclick="openInventoryDetail(<?= $log['code'] ?>)">
                    Chi tiết
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

<div class="modal fade" id="inventoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-0 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-uppercase" id="inventoryModalTitle">Chi tiết phiếu kho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="inventoryModalBody">
          </div>
    </div>
  </div>
</div>

<script src="../js/Admin_XuatNhapKho.js?v=<?= time() ?>"></script>