</main>
<footer class="locknlock-footer">
    <div class="footer-container">
        <div class="footer-top-section">

            <div class="footer-info-col">
                <div class="logo-group">
                    <div class="locknlock-logo">LocknLock</div>
                </div>
                <div class="company-details">
                    <p>
                        CÔNG TY TNHH LOCK & LOCK HCM<br>
                        GCN DKDN: 0309921077 cấp ngày 17/03/2010<br>
                        Nơi cấp: Phòng Đăng ký kinh doanh - Sở Kế hoạch và Đầu tư Tp.HCM<br>
                        Người đại diện: Choi HaeOi<br>
                        Địa chỉ: số 77, đường Hoàng Văn Thái, P.Tân Mỹ, Tp.HCM<br>
                        SDT: 028 3713 5750 (HCM) line 462 hoặc 464 / 024 6293 9370 (Hà Nội)
                    </p>
                </div>
                <div class="social-icons-group">
                    <a href="http://facebook.com/locknlockvietnam" target="_blank" class="social-icon-img-link">
                        <img src="../img_vid/logo_fb.jpg" alt="Facebook" class="social-logo-img">
                    </a>
                    <a href="https://www.instagram.com/locknlockvietnam/" target="_blank" class="social-icon-img-link">
                        <img src="../img_vid/logo_ig.jpg" alt="Instagram" class="social-logo-img">
                    </a>
                    <a href="https://www.tiktok.com/@locknlockvietnam?lang=en" target="_blank" class="social-icon-img-link">
                        <img src="../img_vid/logo_tiktok.jpg" alt="Tiktok" class="social-logo-img">
                    </a>
                    <a href="https://www.youtube.com/@locknlock_vietnam" target="_blank" class="social-icon-img-link">
                        <img src="../img_vid/logo_yt.jpg" alt="Youtube" class="social-logo-img">
                    </a>                           
                </div>
                <div class="trade-logo-link">
                    <a href="http://online.gov.vn/Home/WebDetails/51226?AspxAutoDetectCookieSupport=1" target="_blank">
                      <img src="../img_vid/logo_DaThongBaoBoCongThuong.webp" alt="Đã thông báo Bộ Công Thương" class="bct-logo">
                     </a>
                </div>
            </div>

            <div class="footer-nav-section">
                
                <div class="nav-column">
                    <div class="nav-title"><a href="GioiThieu.php">Giới thiệu</a></div>
                    <ul class="nav-list">
                        <li><a href="GT_ThuongHieu.php">Thương hiệu</a></li>
                        <li><a href="GT_LichSu.php">Lịch sử hình thành</a></li>
                        <li><a href="GT_GiaiThuong.php">Giải thưởng</a></li>
                        <li><a href="GT_ThongTinDoanhNghiep.php">Thông tin doanh nghiệp</a></li>
                        <li><a href="GT_TrachNhiemXaHoi.php">Trách nhiệm xã hội</a></li>
                    </ul>
                </div>

                <div class="nav-column">
                    <div class="nav-title"><a href="SanPham.php?category=all">Sản phẩm</a></div>
                    <ul class="nav-list">
                        <li><a href="SanPham.php?category=1">Bình nước</a></li>
                        <li><a href="SanPham.php?category=2">Đồ dùng nhà bếp</a></li>
                        <li><a href="SanPham.php?category=3">Hộp đựng thực phẩm</a></li>
                        <li><a href="SanPham.php?category=4">Đồ dùng sinh hoạt</a></li>
                        <li><a href="SanPham.php?category=5">Điện Gia Dụng</a></li>
                    </ul>
                </div>

                <div class="nav-column">
                    <div class="nav-title"><a href="DoiMoi.php">Đổi mới</a></div>
                    <ul class="nav-list">
                        <li><a href="DM_LLLabs.php">LL labs</a></li>
                        <li><a href="DM_DesignCenter.php">Design center</a></li>
                    </ul>
                </div>

                <div class="nav-column">
                    <div class="nav-title"><a href="CauChuyen.php">Câu chuyện</a></div>
                    <ul class="nav-list">
                        <li><a href="CC_TinTuc.php">Tin tức</a></li>
                        <li><a href="CC_Lounge.php">Lounge</a></li>
                        <li><a href="CC_SuKien.php">Sự kiện</a></li>
                    </ul>
                </div>

                <div class="nav-column">
                    <div class="nav-title"><a href="HeThongCuaHang.php">Hệ thống</a></div>
                    <ul class="nav-list">
                        <li><a href="HeThongCuaHang.php">Hệ thống</a></li>
                    </ul>
                </div>
                
                <div class="nav-column">
                    <div class="nav-title"><a href="NhuongQuyen.php">Nhượng quyền</a></div>
                    <ul class="nav-list">
                        <li><a href="NhuongQuyen.php">Nhượng quyền</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <div class="policy-links">
                <a href="ChinhSach.php?slug=privacy">Chính sách bảo mật</a>
                <a href="ChinhSach.php?slug=shipping-returns">Chính sách giao hàng, đổi hàng, hoàn tiền</a>
                <a href="ChinhSach.php?slug=warranty">Chính sách bảo hành sản phẩm</a>
                <a href="ChinhSach.php?slug=terms">Các điều khoản và điều kiện</a>
            </div>

            <div class="copyright">
                © 2025. LocknLock All rights reserved.
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siteHeader = document.querySelector('.site-header');
        const searchOpenBtn = document.getElementById('search-open-btn');
        const searchCloseBtn = document.getElementById('search-close-btn');

        if (siteHeader && searchOpenBtn && searchCloseBtn) {
            searchOpenBtn.addEventListener('click', function() {
                siteHeader.classList.add('search-active');
                const searchInput = siteHeader.querySelector('.search-input');
                if (searchInput) searchInput.focus();
            });
            searchCloseBtn.addEventListener('click', function() {
                siteHeader.classList.remove('search-active');
            });
        }
    });
</script>

<script src="../js/TrangChu.js"></script>

<?php if (isset($page_js) && !empty($page_js)): ?>
    <script src="<?php echo $page_js; ?>"></script>
<?php endif; ?>

</body>
</html>