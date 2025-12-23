<?php
    /* KẾT NỐI HEADER */
    // Đường dẫn tương đối từ store_pages vào thư mục includes
    include 'includes/header.php';
?>

<main class="about-page">

    <section class="banner-section">
        <video autoplay muted loop playsinline>
            <source src="../img_vid/GioiThieu1.mp4" type="video/mp4">
        </video>
        <div class="banner-content">
            <h1>ĐỔI MỚI</h1>
            <p>Công nghệ đỉnh cao – Thiết kế tinh tế – Đổi mới truyền cảm hứng</p>
        </div>
    </section>

    <section class="hero-statements">
        <div class="container">
            <h2 class="main-title">Healthier, More convenient Lifestyle creator</h2>
            <p class="subtitle">Nhà sáng tạo phong cách sống toàn cầu cho cuộc sống lành mạnh và tiện lợi hơn</p>

            <p class="lead">
                LocknLock tin rằng điều tốt cho người tiêu dùng cũng tốt cho cả thế giới. Niềm tin này thúc đẩy chúng tôi cam kết
                tạo ra cuộc sống lành mạnh hơn và tiện lợi hơn cho người dùng.
            </p>

            <div class="stats-grid">
                <div class="stat">
                    <div class="stat-number">Since 1978</div>
                    <div class="stat-label">LỊCH SỬ</div>
                </div>
                <div class="stat">
                    <div class="stat-number">43 <span class="muted">yrs</span></div>
                    <div class="stat-label">NĂM HOẠT ĐỘNG</div>
                </div>
                <div class="stat">
                    <div class="stat-number">120</div>
                    <div class="stat-label">MẠNG LƯỚI TOÀN CẦU (quốc gia)</div>
                </div>
                <div class="stat">
                    <div class="stat-number">Sản phẩm đổi mới</div>
                    <div class="stat-label">ĐẶC ĐIỂM</div>
                </div>
            </div>
        </div>
    </section>

    <section class="highlights">
        <div class="container grid-3">

            <article class="card">
                <img src="../img_vid/GioiThieu2.png" alt="Brand story">
                <h3>Câu chuyện thương hiệu</h3>
                <p>Chúng tôi tạo ra sản phẩm kết nối cuộc sống của con người với năng lượng tích cực và cảm xúc tươi sáng.</p>
                <button class="btn view-more" data-target="modal-brand">Xem thêm</button>
            </article>

            <article class="card">
                <img src="../img_vid/GioiThieu3.png" alt="Transformation">
                <h3>Chuyển mình & Đổi mới</h3>
                <p>Lịch sử chuyển mình từ hộp bảo quản thực phẩm sang thương hiệu phong cách sống toàn cầu.</p>
                <button class="btn view-more" data-target="modal-transform">Xem thêm</button>
            </article>

            <article class="card">
                <img src="../img_vid/GioiThieu4.png" alt="Creator">
                <h3>Sáng tạo phong cách</h3>
                <p>Thiết kế lấy con người làm trung tâm — quan sát thói quen, dự đoán tương lai và dẫn dắt xu hướng.</p>
                <button class="btn view-more" data-target="modal-creator">Xem thêm</button>
            </article>

        </div>
    </section>

    <section class="detail-section container">
        <div class="detail-grid">
            <div class="detail-box">
                <h3>Tư duy thiết kế lấy con người làm trung tâm</h3>
                <p>Người tiêu dùng là trọng tâm của mọi hành động. Chúng tôi nhìn về tương lai dựa trên việc quan sát sự thay đổi trong lối sống và thấu hiểu nhu cầu thực tiễn.</p>
            </div>
            <div class="detail-box">
                <h3>Tinh thần LocknLock</h3>
                <p>Cộng tác để tận dụng thế mạnh, tin tưởng lẫn nhau và hành động vì lợi ích xã hội — đó là các giá trị cốt lõi của chúng tôi.</p>
            </div>
            <div class="detail-box">
                <h3>Hành động vì môi trường</h3>
                <p>Chúng tôi thúc đẩy vòng tuần hoàn tài nguyên với chiến dịch 'Yêu Trái Đất' và nhiều sáng kiến khác nhằm giảm thiểu tác động tới môi trường.</p>
            </div>
        </div>
    </section>

    <section class="awards-section">
        <div class="container">
            <h2>Giải thưởng & Thành tựu</h2>
            <p>LocknLock đã được công nhận về thiết kế và đổi mới trên toàn cầu.</p>

            <div class="awards-grid">
                <div class="award-card">
                    <img src="../img_vid/GioiThieu5.png" alt="Red Dot">
                    <h4>Giải Red Dot</h4>
                    <p>Vinh danh thiết kế sáng tạo.</p>
                </div>

                <div class="award-card">
                    <img src="../img_vid/GioiThieu6.png" alt="iF Design">
                    <h4>Giải iF Design</h4>
                    <p>Khẳng định vị thế thiết kế quốc tế.</p>
                </div>

                <div class="award-card">
                    <img src="../img_vid/GioiThieu7.png" alt="Good Design">
                    <h4>Giải Good Design</h4>
                    <p>Thiết kế mang giá trị thực cho cuộc sống.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="modal-brand" aria-hidden="true">
        <div class="modal-inner">
            <button class="modal-close">&times;</button>
            <h3>Câu chuyện thương hiệu</h3>
            <p>LocknLock tạo ra sản phẩm giúp người dùng sống tiện lợi, lành mạnh, và bền vững. Chúng tôi nâng niu trải nghiệm hàng ngày qua thiết kế và công nghệ.</p>
        </div>
    </div>

    <div class="modal" id="modal-transform" aria-hidden="true">
        <div class="modal-inner">
            <button class="modal-close">&times;</button>
            <h3>Chuyển mình & Đổi mới</h3>
            <p>Từ 1978, LocknLock đã mở rộng tầm nhìn vượt ra ngoài sản phẩm bảo quản: trở thành thương hiệu phong cách sống, dẫn dắt xu hướng toàn cầu.</p>
        </div>
    </div>

    <div class="modal" id="modal-creator" aria-hidden="true">
        <div class="modal-inner">
            <button class="modal-close">&times;</button>
            <h3>Sáng tạo phong cách sống</h3>
            <p>Chúng tôi nghiên cứu thói quen, kết hợp công nghệ và thẩm mỹ để mang lại sản phẩm thực tiễn, bền đẹp và thân thiện với người dùng.</p>
        </div>
    </div>

    <button id="backToTop" title="Lên đầu trang">↑</button>

</main>

<link rel="stylesheet" href="../css/GioiThieu.css" />
<script src="../js/GioiThieu.js"></script>

<?php
    /* KẾT NỐI FOOTER */
    include 'includes/footer.php';
?>