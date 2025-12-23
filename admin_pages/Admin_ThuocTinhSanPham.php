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
        <h2 class="fw-bold mb-0">Thuộc tính sản phẩm</h2>
        <button class="btn btn-dark btn-sm" id="btnAddAttr">+ Thêm thuộc tính</button>
      </div>

      <!-- LIST -->
      <div class="card p-4">
        <table class="table table-bordered align-middle">
          <thead class="table-light text-center">
            <tr>
              <th width="25%">Thuộc tính</th>
              <th>Giá trị</th>
              <th width="15%">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($attributes as $attr): ?>
              <tr data-name="<?= $attr['name'] ?>">
                <td class="fw-bold"><?= $attr['name'] ?></td>
                <td>
                  <?php foreach ($attr['values'] as $v): ?>
                    <span class="border px-2 py-1 me-1 small"><?= $v ?></span>
                  <?php endforeach; ?>
                </td>
                <td class="text-center">
                  <button class="btn btn-outline-dark btn-sm btn-edit">Sửa</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="attrModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="attrForm">
        <div class="modal-header">
          <h5 class="modal-title" id="attrModalTitle">Thêm thuộc tính</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="MaThuocTinh" id="MaThuocTinh">

          <div class="mb-3">
            <label class="form-label">Tên thuộc tính</label>
            <input type="text" name="TenThuocTinh" id="TenThuocTinh" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Loại dữ liệu</label>
            <select name="LoaiDuLieu" id="LoaiDuLieu" class="form-select">
              <option value="TEXT">Text</option>
              <option value="NUMBER">Number</option>
              <option value="SELECT">Select</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Giá trị thuộc tính</label>
            <input type="text" name="GiaTri" id="GiaTri" class="form-control"
                   placeholder="Đỏ, Xanh, Vàng">
            <small class="text-muted">Phân cách bằng dấu phẩy</small>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-dark btn-sm">Lưu</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../js/Admin_ThuocTinhSanPham.js"></script>
