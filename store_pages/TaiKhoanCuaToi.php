<?php
// FILE: store_pages/TaiKhoan.php
$page_title = "Tài Khoản Của Tôi | LocknLock";
$page_css = "../css/TaiKhoanCuaToi.css";
$page_js = "../js/TaiKhoanCuaToi.js";
include 'includes/header.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='DangNhap.php';</script>";
    exit;
}
?>

<div class="container account-container">
    <div class="sidebar">
        <div class="user-brief">
            <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
            <div class="info">
                <strong id="sidebar-username">Đang tải...</strong>
                <div style="font-size: 0.85em; color: #888; margin-top: 2px;">Thành viên</div>
            </div>
        </div>
        
        <ul class="menu-list">
            <li class="menu-item has-sub active main-account">
                <span class="menu-head"><i class="fas fa-user-circle"></i> Tài khoản của tôi</span>
                <ul class="sub-menu">
                    <li class="sub-item active" data-target="profile">Hồ sơ</li>
                    <li class="sub-item" data-target="address">Địa chỉ</li>
                    <li class="sub-item" data-target="password">Đổi mật khẩu</li>
                </ul>
            </li>
            <li class="menu-item" data-target="orders">
                <span class="menu-head"><i class="fas fa-file-invoice-dollar"></i> Đơn mua</span>
            </li>
            <li class="menu-item" data-target="wishlist">
                <span class="menu-head"><i class="fas fa-heart"></i> Danh sách yêu thích</span>
            </li>
            <li class="menu-item" data-target="recent">
                <span class="menu-head"><i class="fas fa-history"></i> Đã xem gần đây</span>
            </li>
            <li class="menu-item" data-target="reviews">
                <span class="menu-head"><i class="fas fa-star"></i> Đánh giá đơn hàng</span>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div id="section-profile" class="content-section active">
            <div class="section-header"><h3>Hồ Sơ Của Tôi</h3><p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p></div>
            <form id="form-profile">
                <div class="form-group"><label>Họ & Tên đệm</label><input type="text" name="ho" id="profile-ho"></div>
                <div class="form-group"><label>Tên</label><input type="text" name="ten" id="profile-ten"></div>
                <div class="form-group"><label>Email</label><input type="text" id="profile-email" readonly class="readonly-input"></div>
                <div class="form-group"><label>Số điện thoại</label><input type="text" name="sdt" id="profile-sdt"></div>
                <button type="submit" class="btn-primary">Lưu Thay Đổi</button>
            </form>
        </div>

        <div id="section-address" class="content-section">
            <div class="section-header d-flex justify-content-between"><h3>Địa Chỉ Của Tôi</h3><button class="btn-primary" onclick="openAddressModal()">+ Thêm Địa Chỉ</button></div>
            <div id="address-list"></div>
        </div>

        <div id="section-password" class="content-section">
            <div class="section-header"><h3>Đổi Mật Khẩu</h3></div>
            <form id="form-password">
                <div class="form-group"><label>Mật khẩu hiện tại</label><input type="password" name="old_pass" required></div>
                <div class="form-group"><label>Mật khẩu mới</label><input type="password" name="new_pass" required></div>
                <div class="form-group"><label>Xác nhận</label><input type="password" name="confirm_pass" required></div>
                <button type="submit" class="btn-primary">Xác Nhận</button>
            </form>
        </div>

        <div id="section-orders" class="content-section">
            <div class="tabs-bar">
                <button class="tab-btn active" data-status="all">Tất cả</button>
                <button class="tab-btn" data-status="pending">Chờ xác nhận</button>
                <button class="tab-btn" data-status="shipping">Vận chuyển</button>
                <button class="tab-btn" data-status="completed">Hoàn thành</button>
                <button class="tab-btn" data-status="cancelled">Đã hủy</button>
            </div>
            <div class="order-search"><i class="fas fa-search"></i><input type="text" placeholder="Tìm kiếm đơn hàng..."></div>
            <div id="order-list-container"><div class="loading">Đang tải đơn hàng...</div></div>
        </div>

        <div id="section-wishlist" class="content-section">
             <div class="section-header"><h3>Sản Phẩm Yêu Thích</h3></div>
             <div id="wishlist-grid" class="product-grid-account"></div>
        </div>

        <div id="section-recent" class="content-section">
             <div class="section-header"><h3>Đã Xem Gần Đây</h3></div>
             <div id="recent-grid" class="product-grid-account"></div>
        </div>
        
        <div id="section-reviews" class="content-section">
             <div class="tabs-bar tabs-review">
                <button class="tab-btn active" data-type="not_reviewed">Chưa đánh giá</button>
                <button class="tab-btn" data-type="reviewed">Đã đánh giá</button>
            </div>
            <div id="review-list-container" class="mt-3"></div>
        </div>
    </div>
</div>

<div id="address-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAddressModal()">&times;</span>
        <h3 id="modal-title">Thêm địa chỉ mới</h3>
        <form id="form-address-modal">
            <input type="hidden" name="id" id="addr-id">
            <div class="form-group"><input type="text" name="name" id="addr-name" placeholder="Họ tên" required></div>
            <div class="form-group"><input type="text" name="phone" id="addr-phone" placeholder="SĐT" required></div>
            <div class="form-group"><textarea name="address" id="addr-text" placeholder="Địa chỉ chi tiết" required></textarea></div>
            <div class="form-group checkbox-group"><input type="checkbox" id="addr-default" name="is_default" value="true"><label for="addr-default">Mặc định</label></div>
            <div class="modal-footer"><button type="button" class="btn-sec" onclick="closeAddressModal()">Trở lại</button><button type="submit" class="btn-primary">Hoàn thành</button></div>
        </form>
    </div>
</div>

<div id="cancel-modal" class="modal">
    <div class="modal-content" style="width: 400px;">
        <span class="close-modal" onclick="closeCancelModal()">&times;</span>
        <h3>Lý Do Hủy Đơn</h3>
        <div class="form-group">
            <select id="cancel-reason" style="width:100%; padding:10px; border:1px solid #ddd; margin-top:10px;">
                <option value="Muốn thay đổi địa chỉ giao hàng">Muốn thay đổi địa chỉ giao hàng</option>
                <option value="Muốn thay đổi sản phẩm trong đơn">Muốn thay đổi sản phẩm trong đơn</option>
                <option value="Thủ tục thanh toán quá rắc rối">Thủ tục thanh toán quá rắc rối</option>
                <option value="Tìm thấy giá rẻ hơn ở nơi khác">Tìm thấy giá rẻ hơn ở nơi khác</option>
                <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                <option value="Khác">Lý do khác</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-sec" onclick="closeCancelModal()">Không hủy</button>
            <button type="button" class="btn-primary" id="btn-confirm-cancel">Xác nhận hủy</button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>