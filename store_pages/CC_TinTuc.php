<?php 
    // KHỐI CẤU HÌNH TRANG
    $page_title = "Tin Tức | LocknLock";
    // đường dẫn css (từ store_pages lùi ra root vào css)
    $page_css = "../css/CC_TinTuc.css";
    // đường dẫn js (từ store_pages lùi ra root vào js)
    $page_js = "../js/CC_TinTuc.js";

    // KHỐI INCLUDE HEADER
    // header nằm trong thư mục includes của store_pages
    include 'includes/header.php'; 
?>

<div class="news-container">
    <h1 class="news-title">TIN TỨC</h1>

    <div class="filter-bar">
        <button class="filter-btn active" data-category="all">Tất cả</button>
        <button class="filter-btn" data-category="product">Sản phẩm</button>
        <button class="filter-btn" data-category="company">Công ty</button>
    </div>

    <div class="articles-grid" id="articles-grid">
        </div>

    <div class="pagination-bar" id="pagination-bar">
        </div>
</div>

<?php 
    // KHỐI INCLUDE FOOTER
    include 'includes/footer.php'; 
?>