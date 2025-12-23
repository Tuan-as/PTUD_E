<?php 
    // Định nghĩa các biến cho header
    $page_title = "Design Center | LocknLock Việt Nam";
    $page_css = "../css/DM_DesignCenter.css";  
    $page_js = "../js/DM_DesignCenter.js";  

    // Gắn Header 
    include 'includes/header.php'; 
?>

<main class="design-page">
    <section class="banner-section">
        <img src="../img_vid/DM_DesignCenter1.png" alt="Design Center">
        <div class="banner-content">
            <h1>Design Center</h1>
            <p>Trung tâm thiết kế sáng tạo mang đến những giải pháp sống hiện đại và tiện nghi.</p>
        </div>
    </section>

    <section class="intro-section">
        <div class="intro-content">
            <h1>Về LocknLock Design Center</h1>
            <p>
                LocknLock Design Center là nơi khơi nguồn cho các ý tưởng sáng tạo đột phá,
                kết hợp giữa thiết kế thẩm mỹ và tính ứng dụng cao. Chúng tôi không chỉ tạo ra sản phẩm,
                mà còn kiến tạo trải nghiệm sống tối giản và tiện nghi.
            </p>
        </div>
        <div class="intro-image">
            <img src="../img_vid/DM_DesignCenter2.png" alt="Design Center Studio">
        </div>
    </section>

    <section class="content-section">
        <div class="content-block">
            <div class="content-text">
                <h2>Triết lý thiết kế</h2>
                <p>
                    Mỗi thiết kế của LocknLock đều hướng đến sự đơn giản, thân thiện và gần gũi với cuộc sống hàng ngày.
                    Trung tâm tập trung nghiên cứu về công thái học, vật liệu bền vững và tính năng sử dụng hiệu quả.
                </p>
            </div>
        </div>

        <div class="content-block">
            <div class="content-img">
                <img src="../img_vid/DM_DesignCenter3.png" alt="Không gian sáng tạo">
            </div>
            <div class="content-text">
                <h2>Không gian sáng tạo</h2>
                <p>
                    Với hệ thống studio thiết kế hiện đại, đội ngũ của chúng tôi có thể thử nghiệm,
                    mô phỏng và tinh chỉnh từng chi tiết để mang đến những sản phẩm tốt nhất.
                </p>
            </div>
        </div>

        <section class="overlay-section">
            <div class="overlay-image">
                <img src="../img_vid/DM_DesignCenter4.png" alt="LL Labs Overlay">
                <div class="overlay-content">
                    <h2>LL Labs</h2>
                    <p>Chúng tôi không ngừng sáng tạo để mang đến cho bạn một phong cách sống tiện nghi và bền vững hơn.</p>
                    <a href="DM_LLLabs.html" class="overlay-btn">View more</a>
                </div>
            </div>
        </section>
    </section>
</main>

<button id="backToTop" title="Lên đầu trang">↑</button>

<?php 
    // Gắn Footer  
    include 'includes/footer.php'; 
?>