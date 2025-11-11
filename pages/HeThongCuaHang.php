<?php 
    // Định nghĩa các biến cho header
    $page_title = "Hệ thống cửa hàng | LocknLock";
    $page_css = "../css/HeThongCuaHang.css"; // Link CSS riêng cho trang này
    // Định nghĩa JS riêng cho trang này
    $page_js = "../js/HeThongCuaHang.js";

    // Gắn Header (đã bao gồm <head>, <body>, <header>)
    include '../includes/header.php'; 
?>

<div class="store-container" id="top">
    
    <div class="store-sidebar">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Tìm kiếm cửa hàng bạn đang tìm" />
            <i>🔍</i>
        </div>

        <div class="filter-group">
            <button class="filter-btn active" data-region="all">Xem tất cả</button>
            <button class="filter-btn" data-region="hn">Hà Nội</button>
            <button class="filter-btn" data-region="hcm">Hồ Chí Minh</button>
            <button class="filter-btn" data-region="bac">Miền Bắc</button>
            <button class="filter-btn" data-region="trung">Miền Trung</button>
            <button class="filter-btn" data-region="nam">Miền Nam</button>
        </div>

        <ul class="store-list" id="storeList">
            </ul>
    </div>

    <div class="map-section">
        <iframe id="mapFrame" src="https://www.google.com/maps?q=Vincom%20Mega%20Mall%20Smart%20City&output=embed"></iframe>

        <div class="store-info" id="storeInfo">
            <h3>Vincom Mega Mall Smart City</h3>
            <p><strong>Địa chỉ:</strong> L2-04A, Tầng 2, KĐT Vinhomes Smart City, Nam Từ Liêm, Hà Nội</p>
            <p><strong>Giờ mở cửa:</strong> 10:00 - 21:30</p>
            <p><strong>Điện thoại:</strong> 024-3202-2208</p>
        </div>
    </div>
</div>

<?php 
    // Gắn Footer (đã bao gồm <footer>, <script>, </body>, </html>)
    // Footer sẽ tự động tải file HeThongCuaHang.js
    include '../includes/footer.php'; 
?>