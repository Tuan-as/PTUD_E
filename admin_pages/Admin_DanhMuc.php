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
      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Danh mục sản phẩm</h2>
        <button class="btn btn-dark btn-sm">+ Thêm mới</button>
      </div>

      <!-- =======================
           DANH MỤC
      ======================= -->
      <div class="card border-0 rounded-0 p-4 mb-5">
        <h5 class="fw-bold mb-3">Danh sách danh mục</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="bg-light">
              <tr>
                <th class="text-start">Tên danh mục</th>
                <th>Số sản phẩm</th>
                <th class="text-start">Mô tả</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>

            <?php if (!empty($categories)): ?>
              <?php foreach ($categories as $cat): ?>
                <tr data-id="<?= $cat['MaDanhMuc'] ?>">
                  <td class="text-start fw-bold">
                    <?= htmlspecialchars($cat['TenDanhMuc']) ?>
                  </td>
                  <td><?= (int)$cat['so_san_pham'] ?></td>
                  <td class="text-start"><?= htmlspecialchars($cat['MoTa'] ?? '') ?></td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-dark btn-view">Xem</button>
                      <button type="button" class="btn btn-outline-dark btn-edit">Sửa</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>

            <?php else: ?>
              <tr>
                <td colspan="4" class="text-muted">
                  Chưa có danh mục nào
                </td>
              </tr>
            <?php endif; ?>

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
  <!-- Modal Thêm/Sửa danh mục -->
  <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="categoryForm">
          <div class="modal-header">
            <h5 class="modal-title" id="categoryModalTitle">Thêm danh mục</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="MaDanhMuc" id="MaDanhMuc" value="">

            <div class="mb-3">
              <label for="TenDanhMuc" class="form-label">Tên danh mục</label>
              <input type="text" name="TenDanhMuc" id="TenDanhMuc" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="MoTa" class="form-label">Mô tả</label>
              <textarea name="MoTa" id="MoTa" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-dark btn-sm">Lưu</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<script src="../js/Admin_DanhMuc.js"></script>