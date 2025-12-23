<!-- OFFCANVAS (Mobile) -->
<div class="offcanvas offcanvas-start d-lg-none"
     tabindex="-1"
     id="adminSidebar">

  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu quản trị</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body p-0">
    <?php include 'sidebar_menu.php'; ?>
  </div>
</div>

<!-- SIDEBAR DESKTOP -->
<div class="col-lg-2 d-none d-lg-block bg-white border-end min-vh-100 p-0">
  <?php include 'sidebar_menu.php'; ?>
</div>
