<?php 
    // Định nghĩa các biến cho header
    $page_title = "Câu Chuyện | LocknLock";
    $page_css = "../css/CauChuyen.css"; // Link CSS riêng cho trang này

    // Gắn Header  
    include '../includes/header.php'; 
?>

<div class="page-title-container">
    <h1 class="page-title">CÂU CHUYỆN</h1>
</div>

<section class="section-container section-margin-top">
    <div class="section-header">
        <h2>TIN TỨC</h2>
        <a href="CC_TinTuc.php" class="view-all-btn">Xem thêm</a>
    </div>
    <div class="story-slider-wrapper">
        <div class="story-slider" id="news-section-story">
            </div>
    </div>
</section>

<div class="section-background-gray section-margin-top">
    <section class="section-container">
        <div class="section-header">
            <h2>LOUNGE</h2>
            <a href="CC_Lounge.php" class="view-all-btn">Xem thêm</a>
        </div>
        
        <div class="lounge-slider-wrapper">
             <div class="lounge-slider" id="lounge-section-story">
                </div>
        </div>
    </section>
</div>

<section class="section-container section-margin-top">
    <div class="section-header">
        <h2>SỰ KIỆN</h2>
        <a href="CC_SuKien.php" class="view-all-btn">Xem thêm</a>
    </div>
    <div class="story-slider-wrapper">
         <div class="story-slider" id="events-section-story">
            </div>
    </div>
</section>

<?php 
    // Gắn Footer (đã bao gồm <footer>, <script>, </body>, </html>)
    include '../includes/footer.php'; 
?>