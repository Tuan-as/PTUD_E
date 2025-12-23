<?php
include 'includes/db.php'; // kết nối PDO
include 'includes/header.php';

// Lấy danh mục từ DB để hiển thị dropdown
$stmtDM = $pdo->query("SELECT MaDanhMuc, TenDanhMuc FROM DanhMucSanPham ORDER BY TenDanhMuc ASC");
$categories = $stmtDM->fetchAll(PDO::FETCH_ASSOC);

// Lấy tham số tìm kiếm từ GET
$searchName = $_GET['search_name'] ?? '';
$searchCategory = $_GET['search_category'] ?? '';

// Truy vấn lấy sản phẩm + danh mục
$sql = "SELECT SPU.MaSPU, SPU.TenSanPham, SPU.MoTaNgan, SPU.MoTaDai, SPU.NgayTao, DM.TenDanhMuc
        FROM SPU
        LEFT JOIN DanhMucSanPham DM ON SPU.MaDanhMuc = DM.MaDanhMuc
        WHERE 1";

$params = [];

if (!empty($searchName)) {
    $sql .= " AND SPU.TenSanPham LIKE ?";
    $params[] = '%' . $searchName . '%';
}

if (!empty($searchCategory)) {
    $sql .= " AND SPU.MaDanhMuc = ?";
    $params[] = $searchCategory;
}

$sql .= " ORDER BY SPU.NgayTao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2 class="fw-bold mb-0">Quản lý sản phẩm</h2>
        <button class="btn btn-dark btn-sm" onclick="openProductForm()">+ Thêm sản phẩm</button>
      </div>

      <!-- SEARCH & FILTER -->
      <div class="card border-0 rounded-0 p-4 mb-4">
        <form class="row g-3" method="get">

          <div class="col-md-6">
            <label class="form-label small">Tìm kiếm sản phẩm</label>
            <input type="text" name="search_name" class="form-control" placeholder="Tên sản phẩm" value="<?= htmlspecialchars($searchName) ?>">
          </div>

          <div class="col-md-4">
            <label class="form-label small">Danh mục</label>
            <select class="form-select" name="search_category">
              <option value="">Tất cả</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['MaDanhMuc'] ?>" <?= ($c['MaDanhMuc'] == $searchCategory) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['TenDanhMuc']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-dark btn-sm w-100" type="submit">Tìm kiếm</button>
          </div>

        </form>
      </div>

      <!-- PRODUCT LIST -->
      <div class="card border-0 rounded-0 p-4 mb-5">
        <h5 class="fw-bold mb-3">Danh sách sản phẩm</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="bg-light text-center">
              <tr>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Mô tả ngắn</th>
                <th>Mô tả dài</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $p): ?>
              <tr>
                <td><strong><?= htmlspecialchars($p['TenSanPham']) ?></strong></td>
                <td class="text-center"><?= $p['TenDanhMuc'] ?? '-' ?></td>
                <td><?= nl2br(htmlspecialchars($p['MoTaNgan'])) ?></td>
                <td><?= nl2br(htmlspecialchars($p['MoTaDai'])) ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($p['NgayTao'])) ?></td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-dark" onclick="toggleVariants(this, <?= $p['MaSPU'] ?>)">Xem biến thể</button>
                    <button class="btn btn-outline-dark" onclick="openProductForm(<?= $p['MaSPU'] ?>)">Sửa</button>
                    <button class="btn btn-outline-danger" onclick="deleteSPU(<?= $p['MaSPU'] ?>)">Xoá</button>
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

<!-- MODAL -->
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

<script src="../js/Admin_SanPham.js"></script>
