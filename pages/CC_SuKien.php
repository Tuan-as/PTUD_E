<?php 
    // Định nghĩa các biến cho header
    $page_title = "Sự Kiện | LocknLock";
    $page_css = "../css/CC_SuKien.css";
    // Định nghĩa JS riêng cho trang này
    $page_js = "../js/CC_SuKien.js";

    // Gắn Header
    include '../includes/header.php'; 
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
    // Gắn Footer (footer sẽ tự động tải $page_js ở trên)
    include '../includes/footer.php'; 
?>