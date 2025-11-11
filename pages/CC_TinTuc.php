<?php 
    // Định nghĩa các biến cho header
    $page_title = "Tin Tức | LocknLock";
    $page_css = "../css/CC_TinTuc.css";
    // Định nghĩa JS riêng cho trang này
    $page_js = "../js/CC_TinTuc.js";

    // Gắn Header
    include '../includes/header.php'; 
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
    // Gắn Footer (footer sẽ tự động tải $page_js ở trên)
    include '../includes/footer.php'; 
?>