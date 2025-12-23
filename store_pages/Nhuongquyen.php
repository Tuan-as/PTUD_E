<?php 
    // Định nghĩa các biến cho header
    $page_title = "LocknLock - Trang nhượng quyền & Sản phẩm nổi bật";
    // Đường dẫn CSS lùi ra 1 cấp để về thư mục gốc
    $page_css = "../css/NhuongQuyen.css"; 

    // Gắn Header  
    include 'includes/header.php'; 
?>

<main class="franchise-page">

   <div class="hero-section">
       <div class="hero-overlay"></div>
       <div class="hero-content">
           <h1>Sở hữu ngay cửa hàng LocknLock của chính mình</h1>
           <p>Bạn đang tìm kiếm cơ hội kinh doanh? LocknLock mang đến cơ hội thành công cùng thương hiệu mạnh mẽ và sản phẩm chất lượng.</p>
           
            <div class="hero-buttons">
                 <a href="#lien-he" class="btn btn-primary">Liên hệ</a>
   
                 <a href="TrangChu.php" class="btn btn-secondary">Trang chủ</a>
            </div>
       </div>
   </div>

   <div class="slider-wrapper">
       <div class="slider-arrow" id="prevArrow"><i>&lt;</i></div>
       <div class="slider-arrow" id="nextArrow"><i>&gt;</i></div>
       <div class="slider-content" id="sliderContent"></div>
   </div>

   <div class="reason-section-standalone">
       <h2>Vì sao nên chọn Nhượng quyền thương hiệu LocknLock</h2>
       <div class="reasons-grid-standalone">
           <div class="reason-item-standalone">
               <img src="../img_vid/NQ_reason_sku.jpg" alt="Đa dạng SKU">
               <h4><span>1</span> Hơn 2.000 SKU</h4>
               <p>Đa dạng sản phẩm phù hợp mọi quy mô và ngân sách.</p>
           </div>
           <div class="reason-item-standalone">
               <img src="../img_vid/NQ_reason_brand.jpeg" alt="Thương hiệu uy tín">
               <h4><span>2</span> Thương hiệu uy tín</h4>
               <p>Uy tín LocknLock đã được khẳng định trên toàn cầu.</p>
           </div>
           <div class="reason-item-standalone">
               <img src="../img_vid/NQ_reason_support.jpg" alt="Hỗ trợ toàn diện">
               <h4><span>3</span> Hỗ trợ toàn diện</h4>
               <p>Tư vấn thiết kế, vận hành, marketing chuyên nghiệp.</p>
           </div>
           <div class="reason-item-standalone">
               <img src="../img_vid/NQ_reason_strategy.jpg" alt="Chiến lược">
               <h4><span>4</span> Hướng dẫn chiến lược</h4>
               <p>Được đồng hành cùng đội ngũ chuyên gia kinh doanh.</p>
           </div>
       </div>
   </div>

   <div class="video-section">
       <h2>Lý do tại sao sản phẩm của chúng tôi nổi bật</h2>
       <p>LocknLock không chỉ bán sản phẩm mà mang đến trải nghiệm sống tốt hơn cho khách hàng.</p>
       <div class="video-grid">
           <div class="video-item">
               <a href="https://youtu.be/rKlEpL7jyQg" target="_blank">
                   <div class="video-item-placeholder">
                       <img src="../img_vid/NQ_video_thumbnail_1.jpg" alt="Video 1">
                       <div class="play-icon"></div>
                   </div>
               </a>
           </div>
           <div class="video-item">
               <a href="https://youtu.be/9sm-dJUDJyM" target="_blank">
                   <div class="video-item-placeholder">
                       <img src="../img_vid/NQ_video_thumbnail_2.jpg" alt="Video 2">
                       <div class="play-icon"></div>
                   </div>
               </a>
           </div>
           <div class="video-item">
               <a href="https://youtu.be/cHhqEazsQUE" target="_blank">
                   <div class="video-item-placeholder">
                       <img src="../img_vid/NQ_video_thumbnail_3.jpg" alt="Video 3">
                       <div class="play-icon"></div>
                   </div>
               </a>
           </div>
       </div>
   </div>

   <div class="franchise-types-section">
       <h2>Các loại cửa hàng nhượng quyền</h2>
       <p>Hãy xem nhanh các cửa hàng nhượng quyền theo quy mô.</p>
      
       <div class="store-grid">
           <div class="store-type-card">
               <h3>Nội thất cửa hàng nhượng quyền <span>50m²</span></h3>
               <p>Một cửa hàng nhượng quyền LocknLock rộng 50m² có thể chứa khoảng **560 SKU** thuộc cả năm danh mục, thu hút người tiêu dùng bằng cách giới thiệu các SKU 'chủ chốt' thay đổi hàng tháng.</p>
              
               <div class="image-gallery">
                   <img class="main-img" src="../img_vid/NQ_50m_image_1.jpeg" alt="Thiết kế nội thất cửa hàng LocknLock 50m2">
                   <div class="sub-img-grid">
                       <img src="../img_vid/NQ_50m_image_2.jpeg" alt="Hình ảnh chi tiết khu vực thu ngân 50m2">
                       <img src="../img_vid/NQ_50m_image_3.jpeg" alt="Hình ảnh chi tiết khu vực trưng bày 50m2">
                   </div>
               </div>
           </div>

           <div class="store-type-card">
               <h3>Nội thất cửa hàng nhượng quyền <span>100m²</span></h3>
               <p>Một cửa hàng nhượng quyền LocknLock rộng 100m² có thể chứa hơn **1.100 SKU**, bao gồm cả năm danh mục, bao gồm một khu vực an toàn thu hút người tiêu dùng cao cấp với nhiều loại sản phẩm.</p>
              
               <div class="image-gallery">
                   <img class="main-img" src="../img_vid/NQ_100m_image_1.jpeg" alt="Thiết kế nội thất cửa hàng LocknLock 100m2">
                   <div class="sub-img-grid">
                       <img src="../img_vid/NQ_100m_image_2.jpeg" alt="Hình ảnh chi tiết khu vực quầy hàng 100m2">
                       <img src="../img_vid/NQ_100m_image_3.jpeg" alt="Hình ảnh chi tiết khu vực sản phẩm cao cấp 100m2">
                   </div>
               </div>
           </div>
       </div>
   </div>

   <div class="product-trends-section">
       <h2>Sản phẩm theo xu hướng</h2>
       <p>
           Là một thương hiệu toàn cầu được yêu thích tại nhiều quốc gia, LocknLock cung cấp các sản phẩm có màu sắc tinh tế và thiết kế chức năng.
       </p>
      
       <div class="product-slider-container">
           <div class="slider-arrow" id="prevProdArrow">&lt;</div>
          
           <div class="product-item">
               <img src="../img_vid/NQ_product_trend_1.jpg" alt="Bàn chải điện LocknLock">
           </div>
           <div class="product-item">
               <img src="../img_vid/NQ_product_trend_2.jpg" alt="Lò nướng LocknLock">
           </div>
           <div class="product-item">
               <img src="../img_vid/NQ_product_trend_3.jpg" alt="Bộ nồi LocknLock">
           </div>

           <div class="slider-arrow" id="nextProdArrow">&gt;</div>
       </div>

       <div class="award-logos">
           <img src="../img_vid/NQ_logo_german_design.png" alt="German Design Award">
           <img src="../img_vid/NQ_logo_international_design.png" alt="International Design Excellence Awards">
           <img src="../img_vid/NQ_logo_if_design.png" alt="iF Design Award">
           <img src="../img_vid/NQ_logo_reddot.svg" alt="Red Dot Design Award">
       </div>
   </div>

   <div class="media-support-section">
       <h2>Hỗ trợ truyền thông</h2>
       <p>
           Để tăng mức độ nhận diện cửa hàng và phản ứng của khách hàng, chúng tôi cung cấp các tài liệu tiếp thị như bài đăng trên mạng xã hội, video Youtube, quảng cáo kỹ thuật số.
       </p>
      
       <div class="media-grid">
           <div class="media-item"><img src="../img_vid/NQ_media_support_1.jpg" alt="Quảng cáo 1"></div>
           <div class="media-item"><img src="../img_vid/NQ_media_support_2.jpg" alt="Quảng cáo 2"></div>
           <div class="media-item"><img src="../img_vid/NQ_media_support_3.jpg" alt="Quảng cáo 3"></div>
           <div class="media-item"><img src="../img_vid/NQ_media_support_4.jpg" alt="Quảng cáo 4"></div>
           <div class="media-item"><img src="../img_vid/NQ_media_support_5.jpg" alt="Quảng cáo 5"></div>
           <div class="media-item"><img src="../img_vid/NQ_media_support_6.jpg" alt="Quảng cáo 6"></div>
       </div>
   </div>

   <div class="social-links-section">
       <h3>Tham khảo các kênh truyền thông của chúng tôi để xem bạn có thể nhận những hình ảnh và tài liệu gì.</h3>
       <div class="social-buttons">
           <a href="#" target="_blank" class="social-btn">
               <img src="../img_vid/NQ_logo_youtube.png" alt="Youtube Logo" class="social-icon"> Kênh Youtube
           </a>
           <a href="#" target="_blank" class="social-btn">
               <img src="../img_vid/NQ_logo_facebook.webp" alt="Facebook Logo" class="social-icon"> Trang Facebook
           </a>
           <a href="#" target="_blank" class="social-btn">
               <img src="../img_vid/NQ_logo_instagram.webp" alt="Instagram Logo" class="social-icon"> Instagram
           </a>
       </div>
   </div>

   <div class="process-section">
       <h2>Quy Trình Mở Cửa Hàng Nhượng Quyền</h2>
       <div class="process-grid">
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_register.png" alt="Đăng ký"></div>
               <div class="step-title">Đăng ký</div>
               <div class="step-subtitle step-email">franchise@locknlock.com</div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_consult.png" alt="Thảo luận"></div>
               <div class="step-title">Thảo luận với</div>
               <div class="step-subtitle">nhân viên LnL</div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_evaluate.png" alt="Đánh giá"></div>
               <div class="step-title">Đánh giá vị trí</div>
               <div class="step-subtitle">cửa hàng</div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_approve.png" alt="Phê duyệt"></div>
               <div class="step-title">Quy trình phê duyệt</div>
               <div class="step-subtitle">của LnL</div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_check.png" alt="Đặt cọc"></div>
               <div class="step-title">Đặt cọc</div>
               <div class="step-subtitle"></div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_deposit.png" alt="Ký kết"></div>
               <div class="step-title">Kí kết hợp đồng</div>
               <div class="step-subtitle"></div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_contract.png" alt="Xác nhận"></div>
               <div class="step-title">Xác nhận thiết kế</div>
               <div class="step-subtitle"></div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_design.webp" alt="Xây dựng"></div>
               <div class="step-title">Xây dựng nội thất</div>
               <div class="step-subtitle"></div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_training.png" alt="Đào tạo"></div>
               <div class="step-title">Đào tạo & Kiểm tra</div>
               <div class="step-subtitle">cửa hàng</div>
           </div>
           <div class="process-step">
               <div class="step-icon"><img src="../img_vid/NQ_icon_open.png" alt="Mở cửa"></div>
               <div class="step-title">Mở cửa hàng</div>
               <div class="step-subtitle"></div>
           </div>
       </div>
   </div>

   <div class="support-section">
       <h2>Hỗ Trợ Từ Nhãn Hàng</h2>
       <p class="intro">
           Đồng hành cùng LocknLock để đi đến thành công. Với vốn quản lý nhượng quyền đẳng cấp thế giới, LocknLock đặt các đối tác nhượng quyền đến thành công thông qua các sản phẩm cao cấp.
       </p>
       <div class="support-grid">
           <div class="support-item">
               <div class="support-title-group">
                   <div class="support-icon-placeholder"><img src="../img_vid/NQ_icon_consulting_1.png" alt="Tư vấn"></div>
                   <h4>Hỗ trợ tư vấn cửa hàng</h4>
               </div>
               <p>LocknLock phân bổ các tư vấn viên cửa hàng theo khu vực để hỗ trợ bạn trong việc liên lạc với trụ sở chính.</p>
           </div>
           <div class="support-item">
               <div class="support-title-group">
                   <div class="support-icon-placeholder"><img src="../img_vid/NQ_icon_marketing.png" alt="Marketing"></div>
                   <h4>Chiến dịch Marketing</h4>
               </div>
               <p>LocknLock thực hiện các chiến dịch marketing theo mùa và khuyến mãi nhằm thu hút khách hàng đến cửa hàng của bạn.</p>
           </div>
           <div class="support-item">
               <div class="support-title-group">
                   <div class="support-icon-placeholder"><img src="../img_vid/NQ_icon_product_rd.png" alt="R&D"></div>
                   <h4>Trung tâm phát triển sản phẩm</h4>
               </div>
               <p>LocknLock cung cấp các mặt hàng gia dụng đẳng cấp thế giới, được yêu thích trên toàn cầu.</p>
           </div>
           <div class="support-item">
               <div class="support-title-group">
                   <div class="support-icon-placeholder"><img src="../img_vid/NQ_icon_operation.png" alt="Vận hành"></div>
                   <h4>Vận hành xuất sắc</h4>
               </div>
               <p>LocknLock cung cấp cấp hàng tồn kho cho cửa hàng của bạn và đề xuất các sản phẩm phù hợp với khu vực lân cận.</p>
           </div>
       </div>
   </div>

   <div class="cost-section">
       <h2>Chi phí dự kiến mở cửa hàng</h2>
       <p class="intro">Tiết kiệm thời gian tìm kiếm nhà cung cấp nội thất và POS. Đội ngũ nhượng quyền LocknLock hỗ trợ dịch vụ cho bạn ngay khi cần!</p>
       <div class="cost-table-container">
           <table class="cost-table">
               <thead>
                   <tr>
                       <th>Mặt hàng</th>
                       <th>Số tiền</th>
                       <th>Nội dung</th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <td>Chi phí nội thất</td>
                       <td>3 triệu/m² VNĐ</td>
                       <td>Chi phí nội thất thay đổi tùy thuộc vào vị trí và trạng thái của cửa hàng.</td>
                   </tr>
                   <tr>
                       <td>Mua hàng tồn kho ban đầu</td>
                       <td>Từ 100 triệu trở lên</td>
                       <td>Chi phí mua hàng tồn kho tối thiểu.</td>
                   </tr>
                   <tr>
                       <td>Chi phí thiết kế nội thất</td>
                       <td>0</td>
                       <td>Miễn phí</td>
                   </tr>
                   <tr>
                       <td>Thiết bị POS</td>
                       <td>10 triệu VNĐ</td>
                       <td>LocknLock hỗ trợ phần mềm POS</td>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>

   <div class="contact-section-wrapper" id="lien-he">
       <div class="contact-section">
           
           <div class="contact-info-block">
               <h2>Liên hệ ngay</h2>
               <div class="hotline-group">
                   <h3>HCMC</h3>
                   <div class="hotline-details">
                       <p>Hotline 1: <span class="hotline-number">0839 320 099</span></p>
                       <p>Hotline 2: <span class="hotline-number">0907 763 965</span></p>
                   </div>
               </div>
               <div class="hotline-group" style="border-bottom: none; padding-bottom: 0;">
                   <h3>Hà Nội</h3>
                   <div class="hotline-details">
                       <p>Hotline 1: <span class="hotline-number">024 6293 9370</span></p>
                   </div>
               </div>
               <div class="store-image-contact">
                   <img src="../img_vid/NQ_store_front.jpg" alt="Cửa hàng LocknLock" />
               </div>
           </div>
          
           <div class="contact-form-block">
               <form action="#" method="POST">
                   <div class="form-field-group">
                       <label for="name"><span class="required-star">*</span> Họ và tên</label>
                       <input type="text" id="name" name="name" placeholder="Vui lòng nhập họ và tên" required>
                   </div>
                   <div class="form-field-group">
                       <label for="mobile"><span class="required-star">*</span> Số điện thoại</label>
                       <input type="text" id="mobile" name="mobile" placeholder="Vui lòng nhập số điện thoại" required>
                   </div>
                   <div class="form-field-group">
                       <label for="email">Địa chỉ Email</label>
                       <input type="email" id="email" name="email" placeholder="Vui lòng nhập địa chỉ email">
                   </div>
                   <div class="form-field-group">
                       <label>Vị trí cửa hàng muốn mở</label>
                       <div class="location-fields">
                           <select name="city"><option value="">Thành phố</option></select>
                           <select name="district"><option value="">Quận</option></select>
                           <select name="ward"><option value="">Phường</option></select>
                       </div>
                   </div>
                   <div class="form-field-group">
                       <label>Diện tích cửa hàng muốn mở</label>
                       <div class="area-checkbox-group">
                           <label><input type="checkbox" name="area" value="30m"> 30m²</label>
                           <label><input type="checkbox" name="area" value="50m"> 50m²</label>
                           <label><input type="checkbox" name="area" value="100m+"> 100 m² trở lên</label>
                       </div>
                   </div>
                   <div class="form-field-group">
                       <label for="content">Nội dung</label>
                       <textarea id="content" name="content" placeholder="Vui lòng nhập chi tiết."></textarea>
                   </div>
                   <button type="submit" class="btn-franchise-submit">Đăng ký</button>
               </form>
           </div>
       </div>
   </div>

</main>

<script src="../js/NhuongQuyen.js"></script>

<?php 
    // Gắn Footer  
    include 'includes/footer.php'; 
?>