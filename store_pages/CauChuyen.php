<?php 
    // KHỐI CẤU HÌNH TRANG
    $page_title = "Câu Chuyện | LocknLock";
    // đường dẫn css: từ store_pages ra root rồi vào css
    $page_css = "../css/CauChuyen.css"; 

    // KHỐI INCLUDE HEADER
    include 'includes/header.php'; 
?>

<div class="container page-title-container">
    <h1 class="page-title text-center">CÂU CHUYỆN</h1>
</div>

<section class="section-container">
    <div class="row section-header align-items-center">
        <div class="col-6">
            <h2>TIN TỨC</h2>
        </div>
        <div class="col-6 text-end">
            <a href="CC_TinTuc.php" class="view-all-btn">Xem thêm</a>
        </div>
    </div>
    <div class="story-slider-wrapper">
        <div class="story-slider" id="news-section-story">
            </div>
    </div>
</section>

<div class="section-background-gray section-margin-top">
    <section class="section-container">
        <div class="row section-header align-items-center">
            <div class="col-6">
                <h2>LOUNGE</h2>
            </div>
            <div class="col-6 text-end">
                <a href="CC_Lounge.php" class="view-all-btn">Xem thêm</a>
            </div>
        </div>
        
        <div class="lounge-slider-wrapper">
             <div class="lounge-slider" id="lounge-section-story">
                 </div>
        </div>
    </section>
</div>

<section class="section-container section-margin-top">
    <div class="row section-header align-items-center">
        <div class="col-6">
            <h2>SỰ KIỆN</h2>
        </div>
        <div class="col-6 text-end">
            <a href="CC_SuKien.php" class="view-all-btn">Xem thêm</a>
        </div>
    </div>
    <div class="story-slider-wrapper">
         <div class="story-slider" id="events-section-story">
             </div>
    </div>
</section>

<?php 
    // KHỐI INCLUDE FOOTER
    include 'includes/footer.php'; 
?>