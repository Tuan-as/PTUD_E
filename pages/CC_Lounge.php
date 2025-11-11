<?php 
    // Định nghĩa các biến cho header
    $page_title = "Lounge | LocknLock";
    $page_css = "../css/CC_Lounge.css";
    // Định nghĩa JS riêng cho trang này
    $page_js = "../js/CC_Lounge.js";

    // Gắn Header
    include '../includes/header.php'; 
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
    // Gắn Footer (footer sẽ tự động tải $page_js ở trên)
    include '../includes/footer.php'; 
?>