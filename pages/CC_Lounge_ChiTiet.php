<?php 
    // Định nghĩa các biến cho header
    $page_title = "Chi Tiết Lounge | LocknLock";
    $page_css = "../css/CC_Lounge_ChiTiet.css";
    // Định nghĩa JS riêng cho trang này
    $page_js = "../js/CC_Lounge_ChiTiet.js";

    // Gắn Header
    include '../includes/header.php'; 
?>

<div class="detail-container">
    <div id="detail-content">
        Đang tải nội dung...
    </div>
    
    <div class="back-to-news-section">
        <a href="CC_Lounge.php" class="back-button">
            &#9664; Quay lại 
        </a>
    </div>
    
</div>

<?php 
    // Gắn Footer (footer sẽ tự động tải $page_js ở trên)
    include '../includes/footer.php'; 
?>