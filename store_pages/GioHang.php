<?php
$page_title = "Giỏ Hàng | LocknLock";
$page_css = "../css/GioHang.css"; // Dùng file CSS mới
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location='DangNhap.php';</script>";
    exit;
}
?>

<div class="cart-container">
    <h3 class="mb-4 fw-bold text-uppercase">Giỏ Hàng</h3>

    <div class="cart-header">
        <div><input type="checkbox" id="check-all-top" class="custom-checkbox" onchange="toggleAll(this)"></div>
        <div>Sản Phẩm</div>
        <div>Đơn Giá</div>
        <div>Số Lượng</div>
        <div>Thành Tiền</div>
        <div><i class="fas fa-trash-alt"></i></div>
    </div>

    <div id="cart-list">
        <div class="text-center py-5 bg-white">Đang tải giỏ hàng...</div>
    </div>

    <div class="cart-footer">
        <div class="footer-left">
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="check-all-bot" class="custom-checkbox" onchange="toggleAll(this)">
                <label for="check-all-bot" style="cursor:pointer;">Chọn Tất Cả (<span id="count-selected">0</span>)</label>
            </div>
            <button class="btn text-danger fw-bold ms-3" onclick="removeSelected()">Xóa đã chọn</button>
        </div>

        <div class="footer-right">
            <div class="d-flex align-items-center">
                <span class="total-label me-2">Tổng thanh toán:</span>
                <span class="total-price" id="cart-total">0₫</span>
            </div>
            
            <form action="ThanhToan.php" method="POST" id="form-checkout">
                <input type="hidden" name="selected_skus" id="input-selected-skus">
                <button type="button" onclick="goToCheckout()" class="btn-checkout">Mua Hàng</button>
            </form>
        </div>
    </div>
</div>

<script src="../js/GioHang.js"></script>
<?php include 'includes/footer.php'; ?>