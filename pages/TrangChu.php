<?php 
    // Định nghĩa các biến cho header
    $page_title = "Trang Chủ | LocknLock";
    $page_css = "../css/TrangChu.css"; // Link CSS riêng cho trang này

    // Gắn Header (đã bao gồm <head>, <body>, <header>)
    include '../includes/header.php'; 
?>

<section class="video-hero">
    <video id="product-video" autoplay loop muted playsinline poster="../img_vid/video_fallback_image.jpg">
        <source src="../img_vid/trangchu_product_intro_video.webm" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ thẻ video.
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-caption">
        <a href="#" class="detail-button">Xem thêm</a>
    </div>
</section>

<section class="product-slider-wrapper">
    
    <div class="product-slider">
        
        <section class="product-banner-item item-metro-king" style="background-image: url('../img_vid/trangchu_banner3.jpg');">
            <div class="banner-text-content content-bottom-left">
                <h2 class="title-h2">Bình giữ nhiệt Metro King 1.2L</h2>
                <h1 class="title-h1">Biến cuộc sống thường ngày thành phong cách.</h1>
                <p class="description-text">Khả năng giữ nhiệt nóng và lạnh đặc biệt giúp duy trì hương vị thơm ngon ở nhiệt độ lý tưởng, tươi mát và lâu hơn!</p>
                <a href="#" class="banner-button">Xem thêm</a>
            </div>
        </section>

        <section class="product-banner-item item-kimchi-fridge" style="background-image: url('../img_vid/trangchu_banner2.jpg');">
            <div class="banner-text-content content-top-left">
                <h2 class="title-h2">Tủ lạnh Kimchi 50L</h2>
                <h1 class="title-h1">Đầy đủ tiện nghi. <br>Rộng rãi và tươi ngon!</h1>
                <p class="description-text">Dung tích lớn hơn cho không gian rộng rãi hơn! Chế độ lên men kimchi giúp kimchi tươi ngon hơn, và phương pháp làm lạnh trực tiếp giúp kimchi tươi ngon lâu hơn.</p>
                <a href="#" class="banner-button">Xem thêm</a>
            </div>
        </section>

        <section class="product-banner-item item-fresh-protect" style="background-image: url('../img_vid/trangchu_banner1.jpg');">
            <div class="banner-text-content content-top-left">
                <h2 class="title-h2">Giữ trọn sự tươi mới.</h2>
                <h1 class="title-h1">Chân không tùy chỉnh 3 cấp độ <br> FRESH PROTECT.</h1>
                <p class="description-text">Lưu trữ tươi ngon đến tối đa 50 ngày nhờ chế độ chân không tùy chỉnh theo từng loại thực phẩm. Sạch sẽ và tinh tươm lâu dài!</p>
                <a href="#" class="banner-button">Xem thêm</a>
            </div>
        </section>

    </div> 
    
    <button class="slider-control prev-control" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-control next-control" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>

    <div class="slider-dots">
        <span class="dot active" data-slide-index="0"></span>
        <span class="dot" data-slide-index="1"></span>
        <span class="dot" data-slide-index="2"></span>
    </div>

</section>

<section class="birthday-banner-split"> 
    <div class="birthday-image-column" style="background-image: url('../img_vid/trangchu_event_birthday.jpg');">
        </div>
    <div class="birthday-text-column">
        <div class="banner-text-content content-birthday">
            <h2 class="title-h2">HAPPY BIRTHDAY THÁNG 11</h2>
            <h1 class="title-h1">TUỔI MỚI LINH ĐÌNH, RINH DEAL RỘN RÀNG</h1>
            <p class="description-text">Ưu đãi đặc biệt xuyên suốt Tháng 11 thay cho lời chúc mừng sinh nhật tới các thành viên!</p>
            <a href="CC_SuKien.php" class="banner-button">Xem thêm</a>
        </div>
    </div>
</section>

<section class="section-container about-us-section-wrapper section-margin-top">
    <div class="section-header">
        <h2>GIỚI THIỆU</h2>
    </div>

    <div class="about-us-image-map-card" id="aboutUsBackground" style="background-image: url('../img_vid/about_us_default_background.jpg');">
        
        <div class="about-us-overlay"></div> 

        <div class="about-us-menu-container">
            <ul class="about-us-menu-map-list">
                <li data-image="../img_vid/trangchu_about_us_brand.jpg">
                    <a href="#"><h3 class="map-title">Thương hiệu</h3><p class="map-description">Khám phá câu chuyện hình thành và giá trị cốt lõi.</p></a>
                </li>
                <li data-image="../img_vid/trangchu_about_us_history.jpg">
                    <a href="#"><h3 class="map-title">Lịch sử hình thành</h3><p class="map-description">Hành trình phát triển vượt bậc.</p></a>
                </li>
                <li data-image="../img_vid/trangchu_about_us_awards.jpg">
                    <a href="#"><h3 class="map-title">Giải thưởng</h3><p class="map-description">Những thành tựu và giải thưởng uy tín.</p></a>
                </li>
                <li data-image="../img_vid/trangchu_about_us_corporate_info.jpg">
                    <a href="#"><h3 class="map-title">Thông tin doanh nghiệp</h3><p class="map-description">Tìm hiểu về cấu trúc, đội ngũ lãnh đạo.</p></a>
                </li>
                <li data-image="../img_vid/trangchu_about_us_csr.jpg">
                    <a href="#"><h3 class="map-title">Trách nhiệm xã hội</h3><p class="map-description">Nỗ lực vì cộng đồng và môi trường.</p></a>
                </li>
            </ul>
        </div>
    </div>
</section>

<section class="section-container why-choose-section section-margin-top">
    <div class="section-header">
        <h2 class="section">VÌ SAO NÊN CHỌN LOCKNLOCK?</h2>
    </div>
    
    <div class="reason-cards-grid">
        <div class="reason-card reason-1">
            <h3 class="card-title">CÔNG NGHỆ ĐỔI MỚI</h3>
            <p class="card-description">Tiên phong trong các giải pháp bảo quản kín hơi, chân không và công nghệ nấu nướng thông minh.</p>
        </div>
        <div class="reason-card reason-2">
            <h3 class="card-title">CHẤT LIỆU AN TOÀN TUYỆT ĐỐI</h3>
            <p class="card-description">100% không chứa BPA, dùng vật liệu thủy tinh chịu nhiệt và nhựa Ecogen thân thiện môi trường.</p>
        </div>
        <div class="reason-card reason-3">
            <h3 class="card-title">THIẾT KẾ HIỆN ĐẠI</h3>
            <p class="card-description">Đạt nhiều giải thưởng quốc tế, biến đồ gia dụng thành vật phẩm trang trí tinh tế cho ngôi nhà bạn.</p>
        </div>
        <div class="reason-card reason-4">
            <h3 class="card-title">CAM KẾT SỐNG XANH</h3>
            <p class="card-description">Đẩy mạnh sản xuất các dòng Re:Born từ vật liệu tái chế, mục tiêu trung hòa carbon năm 2050.</p>
        </div>
    </div>
</section>

<section class="section-container section-margin-top">
    <div class="section-header">
        <h2>TIN TỨC</h2>
        <a href="CC_TinTuc.php" class="view-all-btn">Xem thêm</a>
    </div>
    <div class="story-slider-wrapper">
        <div class="story-slider" id="news-section">
            </div>
    </div>
    </section>

<section class="section-container section-margin-top">
    <div class="section-header">
        <h2>LOUNGE</h2>
        <a href="CC_Lounge.php" class="view-all-btn">Xem thêm</a>
    </div>
    
    <div class="lounge-slider-wrapper">
        <div class="lounge-slider" id="lounge-section">
            </div>
    </div>
</section>

<section class="section-container section-margin-top">
    <div class="section-header">
        <h2>SỰ KIỆN</h2>
        <a href="CC_SuKien.php" class="view-all-btn">Xem thêm</a>
    </div>
    <div class="story-slider-wrapper">
         <div class="story-slider" id="events-section">
            </div>
    </div>
    </section>

<section class="contact-form-section section-margin-top">
    <div class="contact-header">
                <h2>LIÊN HỆ VÀ GÓP Ý</h2>
    </div>
            
    <div class="contact-grid">
                
        <div class="contact-form-column">
                    <h3 class="sub-header-title">Chúng tôi có thể giúp gì cho bạn?</h3>
                    <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng để lại tin nhắn nhé! Chúng tôi sẽ phản hồi sớm nhất có thể.</p>

                    <form class="contact-form" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã gửi liên hệ!');">
                        <input type="text" placeholder="Họ và Tên" required>
                        <input type="email" placeholder="Địa chỉ Email" required>
                        <input type="text" placeholder="Số điện thoại (Tùy chọn)">
                        <textarea placeholder="Nội dung Tin nhắn" required></textarea>
                        <button type="submit" class="submit-btn">Gửi Liên Hệ</button>
                    </form>
        </div>

        <div class="faq-column">
        </div>

    </div>
</section>

<?php 
    // Gắn Footer (đã bao gồm <footer>, <script>, </body>, </html>)
    include '../includes/footer.php'; 
?>