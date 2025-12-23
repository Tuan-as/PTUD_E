<?php 
    // KHỐI CẤU HÌNH TRANG
    $page_title = "Lounge | LocknLock";
    // đường dẫn file css và js tương đối từ store_pages
    $page_css = "../css/CC_Lounge.css";
    $page_js = "../js/CC_Lounge.js";

    // KHỐI INCLUDE HEADER
    include 'includes/header.php'; 
?>

<div class="news-container">
    <h1 class="news-title">LOUNGE</h1>

    <div class="filter-bar">
        <button class="filter-btn active" data-category="all">Tất cả</button>
        <button class="filter-btn" data-category="tips">Mẹo sử dụng</button>
        <button class="filter-btn" data-category="recipe">Công thức nấu ăn</button>
        <button class="filter-btn" data-category="eco">Sống xanh</button>
    </div>

    <div class="lounge-articles-grid" id="articles-grid">
        </div>

    <div class="pagination-bar" id="pagination-bar">
        </div>
</div>

<?php 
    // KHỐI INCLUDE FOOTER
    include 'includes/footer.php'; 
?>