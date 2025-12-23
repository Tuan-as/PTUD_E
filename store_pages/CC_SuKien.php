<?php 
    // KHỐI CẤU HÌNH TRANG
    $page_title = "Sự Kiện | LocknLock";
    // đường dẫn css từ store_pages ra root vào css
    $page_css = "../css/CC_SuKien.css";
    // đường dẫn js từ store_pages ra root vào js
    $page_js = "../js/CC_SuKien.js";

    // KHỐI INCLUDE HEADER
    // header nằm trong thư mục con includes của store_pages
    include 'includes/header.php'; 
?>

<div class="news-container">
    <h1 class="news-title">SỰ KIỆN</h1>

    <div class="filter-bar">
        <button class="filter-btn active" data-status="all">Tất cả</button>
        <button class="filter-btn" data-status="past">Đã diễn ra</button>
        <button class="filter-btn" data-status="current">Đang diễn ra</button>
        <button class="filter-btn" data-status="upcoming">Sắp diễn ra</button>
    </div>

    <div class="articles-grid" id="events-grid">
        </div>

    <div class="pagination-bar" id="pagination-bar">
        </div>
</div>

<?php 
    // KHỐI INCLUDE FOOTER
    include 'includes/footer.php'; 
?>