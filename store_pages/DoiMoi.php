<?php 
    // Định nghĩa các biến cho header
    $page_title = "Đổi mới | LocknLock Việt Nam";
    $page_css = "../css/DoiMoi.css";  
    $page_js = "../js/DoiMoi.js";  

    // Gắn Header  
    include 'includes/header.php'; 
?>

<main class="innovation-page">
    <section class="banner-section">
        <video autoplay muted loop playsinline>
            <source src="../img_vid/DoiMoi1.mp4" type="video/mp4">
        </video>
        <div class="banner-content">
            <h1>ĐỔI MỚI</h1>
            <p>Công nghệ đỉnh cao – Thiết kế tinh tế – Đổi mới truyền cảm hứng</p>
        </div>
    </section>
    
    <section class="intro-section">
        <div class="intro-content">
            <h1>Đổi mới cùng LocknLock</h1>
            <p>
                LocknLock không ngừng nghiên cứu và phát triển để mang đến những sản phẩm sáng tạo,
                thân thiện với môi trường và đáp ứng nhu cầu ngày càng cao của người tiêu dùng toàn cầu.
            </p>
        </div>
    </section>

    <section class="link-section">
        <div class="link-card">
            <img src="../img_vid/DoiMoi2.png" alt="LL Labs">
            <div class="link-text">
                <h2>LL Labs</h2>
                <p>
                    Trung tâm sáng tạo tập trung vào việc phát triển công nghệ và vật liệu mới,
                    giúp LocknLock đi đầu trong lĩnh vực đồ gia dụng thông minh và thân thiện với môi trường.
                </p>
                <a href="Dm_LLLabs.php" class="btn-more">Khám phá</a>
            </div>
        </div>

        <div class="link-card">
            <img src="../img_vid/DoiMoi3.png" alt="Design Center">
            <div class="link-text">
                <h2>Design Center</h2>
                <p>
                    Nơi hội tụ các nhà thiết kế sáng tạo, tạo ra những sản phẩm mang tính thẩm mỹ cao,
                    tiện dụng và phù hợp với lối sống hiện đại.
                </p>
                <a href="DM_DesignCenter.php" class="btn-more">Khám phá</a>
            </div>
        </div>
    </section>
</main>

<?php 
    // Gắn Footer  
    include 'includes/footer.php'; 
?>