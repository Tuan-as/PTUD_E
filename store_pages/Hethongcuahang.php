<?php
    // Định nghĩa các biến cho header
    $page_title = "Hệ thống cửa hàng | LocknLock Việt Nam";
    // Đường dẫn CSS lùi ra 1 cấp để lấy từ thư mục css gốc
    $page_css = "../css/Hethongcuahang.css";

    // Gắn Header (Nằm trong thư mục store_pages/includes nên gọi trực tiếp)
    include 'includes/header.php';
?>

<div class="store-container">
    
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

        <ul class="store-list" id="storeList"></ul>
    </div>

    <div class="map-section">
        <iframe id="mapFrame" src="https://maps.google.com/maps?q=Vincom%20Mega%20Mall%20Smart%20City&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>

        <div class="store-info" id="storeInfo">
            <h3>Vincom Mega Mall Smart City</h3>
            <p><strong>Địa chỉ:</strong> L2-04A, Tầng 2, KĐT Vinhomes Smart City, Nam Từ Liêm, Hà Nội</p>
            <p><strong>Giờ mở cửa:</strong> 10:00 - 21:30</p>
            <p><strong>Điện thoại:</strong> 024-3202-2208</p>
        </div>
    </div>
</div>

<script src="../js/Hethongcuahang.js"></script>

<?php
    // Gắn Footer (Nằm trong thư mục store_pages/includes)
    include 'includes/footer.php';
?>