<?php 
    // Định nghĩa các biến cho header
    $page_title = "LL Labs | LocknLock Việt Nam";
    $page_css = "../css/Dm_LLLabs.css";  
    $page_js = "../js/Dm_LLLabs.js";  

    // Gắn Header 
    include 'includes/header.php'; 
?>

<main class="labs-page">
    <section class="banner-section">
        <img src="../img_vid/DM_LLLabs1.png" alt="LL Labs">
        <div class="banner-content">
            <h1>LL Labs</h1>
            <p>Đổi mới cuộc sống lấy con người làm trung tâm</p>
        </div>
    </section>

    <section class="intro-section">
        <div class="intro-content">
            <h1>LL Labs</h1>
            <p>
                LL Labs là trung tâm sáng tạo của LocknLock, nơi nghiên cứu và phát triển công nghệ,
                vật liệu mới và các giải pháp cải tiến sản phẩm nhằm mang đến trải nghiệm tốt nhất cho người dùng.
            </p>
        </div>
        <div class="intro-image">
            <img src="../img_vid/DM_LLLabs2.png" alt="LL Labs">
        </div>
    </section>

    <section class="content-section">
        <div class="content-block">
            <div class="content-text">
                <h2>Trung tâm nghiên cứu và phát triển</h2>
                <p>
                    LL Labs tập trung vào việc khám phá các vật liệu thân thiện môi trường,
                    công nghệ lưu trữ tiên tiến và thiết kế bền vững —
                    hướng tới mục tiêu nâng cao chất lượng cuộc sống qua sự tiện nghi và an toàn.
                </p>
            </div>
        </div>

        <div class="content-block">
            <div class="content-img">
                <img src="../img_vid/DM_LLLabs3.png" alt="Không gian sáng tạo mở">
            </div>
            <div class="content-text">
                <h2>Không gian sáng tạo mở</h2>
                <p>
                    Tại LL Labs, các chuyên gia và nhà thiết kế hợp tác thử nghiệm ý tưởng mới,
                    chia sẻ tầm nhìn và tạo ra những sản phẩm có tính ứng dụng cao trong đời sống hàng ngày.
                </p>
            </div>
        </div>
        
        <section class="overlay-section">
            <div class="overlay-image">
                <img src="../img_vid/DM_LLLabs4.png" alt="Design Center">
                <div class="overlay-content">
                    <h2>Design Center</h2>
                    <a href="DM_DesignCenter.html" class="overlay-btn">View more</a>
                </div>
            </div>
        </section>
    </section>
</main>

<?php 
    // Gắn Footer 
    include 'includes/footer.php'; 
?>