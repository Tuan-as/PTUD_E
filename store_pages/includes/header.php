<?php 
    /* * File: includes/header.php */
    
    // Khởi động session nếu chưa có
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo isset($page_title) ? $page_title : "LocknLock Vietnam"; ?></title>
    
    <link rel="stylesheet" href="../css/style.css"> 
    
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* --- CSS HEADER USER & MOBILE (GIỮ NGUYÊN) --- */
        .user-welcome-text { font-size: 0.9em; color: #555; margin-right: 5px; }
        .user-name-link { font-weight: bold; color: #111; text-decoration: none; font-size: 0.95em; margin-right: 15px; transition: 0.2s; }
        .user-name-link:hover { color: #000; border-bottom: 1px solid #000; }
        .btn-logout { color: #999; font-size: 0.9em; transition: 0.2s; }
        .btn-logout:hover { color: #d0021b; }
        
        .mobile-menu-btn { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }
        .mobile-nav { display: none; position: absolute; top: 90px; left: 0; width: 100%; background-color: white; z-index: 999; border-top: 1px solid #eee; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .mobile-nav.active { display: block; }
        .mobile-nav-list { list-style: none; padding: 0; margin: 0; }
        .mobile-nav-item { border-bottom: 1px solid #eee; }
        .mobile-nav-link { display: block; padding: 15px 20px; text-decoration: none; color: #333; font-weight: 500; }
        .mobile-submenu { display: none; background-color: #f9f9f9; padding-left: 20px; }
        .mobile-nav-item.active .mobile-submenu { display: block; }
        .mobile-submenu-link { display: block; padding: 10px 20px; text-decoration: none; color: #555; font-size: 0.9em; }

        @media (max-width: 1024px) {
            .mobile-menu-btn { display: block; }
            .main-nav { display: none; }
        }

        /* --- SEARCH OVERLAY (GIAO DIỆN MỚI - GỌN GÀNG) --- */
        .search-overlay {
            position: fixed; /* Cố định trên cùng */
            top: 0;
            left: 0;
            width: 100%;
            height: auto; /* Chiều cao tự động theo nội dung, không chiếm hết màn hình */
            background-color: #ffffff;
            z-index: 9999;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15); /* Đổ bóng để tách biệt */
            
            /* Hiệu ứng trượt từ trên xuống */
            transform: translateY(-100%); 
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            padding: 40px 0 60px 0;
        }

        .site-header.search-active .search-overlay {
            transform: translateY(0); /* Trượt xuống hiện ra */
        }

        /* Lớp phủ mờ đằng sau (để che web đi khi search hiện ra) */
        .search-backdrop {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            display: none;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .site-header.search-active .search-backdrop {
            display: block;
            opacity: 1;
        }

        /* Container nội dung tìm kiếm */
        .search-container-inner {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            text-align: center;
        }

        /* Nút đóng X */
        .search-close-btn {
            position: absolute;
            top: -20px; /* Đẩy lên góc trên cùng */
            right: 20px;
            font-size: 30px;
            color: #999;
            background: none;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-close-btn:hover { color: #d0021b; transform: rotate(90deg); }

        /* Form tìm kiếm */
        .search-form-overlay {
            position: relative;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            display: flex;
            align-items: center;
        }

        .search-input-overlay {
            width: 100%;
            border: none;
            font-size: 24px; /* Chữ vừa phải, gọn gàng */
            padding: 15px 0;
            outline: none;
            background: transparent;
            font-weight: 500;
            color: #333;
        }
        
        .search-submit-btn-overlay {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #333;
            padding: 0 10px;
        }

        /* Từ khóa nổi bật */
        .recommendation-keywords h3 {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .keywords-list {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .keyword-tag {
            display: inline-block;
            padding: 8px 20px;
            background-color: #f7f7f7;
            color: #555;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .keyword-tag:hover {
            background-color: #fff;
            border-color: #333;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <header class="site-header">
        
        <div class="search-backdrop" id="search-backdrop"></div>

        <div class="nav-bar">
             <div class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>

            <div class="logo-container">
                <a href="TrangChu.php" class="logo-link">LocknLock</a>
            </div>

            <nav class="main-nav">
                <ul class="nav-list">
                    <li class="nav-item has-submenu">
                        <a href="GioiThieu.php">Giới thiệu</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column"><a href="GT_ThuongHieu.php" class="submenu-title">Thương hiệu</a></div>
                                <div class="submenu-column"><a href="GT_LichSu.php" class="submenu-title">Lịch sử hình thành</a></div>
                                <div class="submenu-column"><a href="GT_GiaiThuong.php" class="submenu-title">Giải thưởng</a></div>
                                <div class="submenu-column"><a href="GT_ThongTinDoanhNghiep.php" class="submenu-title">Thông tin doanh nghiệp</a></div>
                                <div class="submenu-column"><a href="GT_TrachNhiemXaHoi.php" class="submenu-title">Trách nhiệm xã hội</a></div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item has-submenu submenu-full">
                        <a href="SanPham.php?category=all">Sản phẩm</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column"><a href="SanPham.php?category=1" class="submenu-title">Bình nước</a></div>
                                <div class="submenu-column"><a href="SanPham.php?category=2" class="submenu-title">Đồ dùng nhà bếp</a></div>
                                <div class="submenu-column"><a href="SanPham.php?category=3" class="submenu-title">Hộp đựng thực phẩm</a></div>
                                <div class="submenu-column"><a href="SanPham.php?category=4" class="submenu-title">Đồ dùng sinh hoạt</a></div>
                                <div class="submenu-column"><a href="SanPham.php?category=5" class="submenu-title">Điện Gia Dụng</a></div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item has-submenu">
                        <a href="DoiMoi.php">Đổi mới</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column"><a href="DM_LLLabs.php" class="submenu-title">LL labs</a></div>
                                <div class="submenu-column"><a href="DM_DesignCenter.php" class="submenu-title">Design center</a></div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item has-submenu">
                        <a href="CauChuyen.php">Câu chuyện</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column"><a href="CC_TinTuc.php" class="submenu-title">Tin tức</a></div>
                                <div class="submenu-column"><a href="CC_Lounge.php" class="submenu-title">Lounge</a></div>
                                <div class="submenu-column"><a href="CC_SuKien.php" class="submenu-title">Sự kiện</a></div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item"><a href="HeThongCuaHang.php">Hệ thống</a></li>
                    <li class="nav-item"><a href="NhuongQuyen.php">Nhượng quyền</a></li>
                </ul>
            </nav>

            <div class="header-controls">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-welcome-text hidden-mobile">Xin chào,</span>
                    <a href="TaiKhoanCuaToi.php" class="user-name-link hidden-mobile"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                    <a href="DangXuat.php" class="btn-logout hidden-mobile"><i class="fas fa-sign-out-alt"></i></a>
                    <a href="TaiKhoanCuaToi.php" class="icon-btn mobile-user-btn" style="display:none;"><i class="fas fa-user"></i></a>
                <?php else: ?>
                    <div class="desktop-auth-btns">
                        <a href="DangNhap.php" class="btn btn-login"><i class="fas fa-user" style="margin-right:5px;"></i> Đăng nhập</a>
                        <a href="DangKy.php" class="btn-register">Đăng ký</a>
                    </div>
                     <a href="DangNhap.php" class="icon-btn mobile-login-btn" style="display:none;"><i class="fas fa-user"></i></a>
                <?php endif; ?>
                
                <a href="GioHang.php" class="icon-btn cart-btn"><i class="fas fa-shopping-cart"></i></a>
                
                <button class="icon-btn search-open-btn" id="search-open-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        
        <nav class="mobile-nav" id="mobile-nav">
            <ul class="mobile-nav-list">
                <li class="mobile-nav-item"><a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">Giới thiệu <i class="fas fa-chevron-down" style="float:right; font-size: 12px; margin-top: 5px;"></i></a>
                    <div class="mobile-submenu">
                        <a href="GT_ThuongHieu.php" class="mobile-submenu-link">Thương hiệu</a>
                        <a href="GT_LichSu.php" class="mobile-submenu-link">Lịch sử hình thành</a>
                        <a href="GT_GiaiThuong.php" class="mobile-submenu-link">Giải thưởng</a>
                        <a href="GT_ThongTinDoanhNghiep.php" class="mobile-submenu-link">Thông tin doanh nghiệp</a>
                        <a href="GT_TrachNhiemXaHoi.php" class="mobile-submenu-link">Trách nhiệm xã hội</a>
                    </div>
                </li>
                 <li class="mobile-nav-item"><a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">Sản phẩm <i class="fas fa-chevron-down" style="float:right; font-size: 12px; margin-top: 5px;"></i></a>
                    <div class="mobile-submenu">
                         <a href="SanPham.php?category=1" class="mobile-submenu-link">Bình nước</a>
                        <a href="SanPham.php?category=2" class="mobile-submenu-link">Đồ dùng nhà bếp</a>
                        <a href="SanPham.php?category=3" class="mobile-submenu-link">Hộp đựng thực phẩm</a>
                        <a href="SanPham.php?category=4" class="mobile-submenu-link">Đồ dùng sinh hoạt</a>
                        <a href="SanPham.php?category=5" class="mobile-submenu-link">Điện Gia Dụng</a>
                    </div>
                </li>
                 <li class="mobile-nav-item"><a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">Đổi mới <i class="fas fa-chevron-down" style="float:right; font-size: 12px; margin-top: 5px;"></i></a>
                    <div class="mobile-submenu">
                        <a href="DM_LLLabs.php" class="mobile-submenu-link">LL labs</a>
                        <a href="DM_DesignCenter.php" class="mobile-submenu-link">Design center</a>
                    </div>
                </li>
                 <li class="mobile-nav-item"><a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">Câu chuyện <i class="fas fa-chevron-down" style="float:right; font-size: 12px; margin-top: 5px;"></i></a>
                    <div class="mobile-submenu">
                        <a href="CC_TinTuc.php" class="mobile-submenu-link">Tin tức</a>
                        <a href="CC_Lounge.php" class="mobile-submenu-link">Lounge</a>
                        <a href="CC_SuKien.php" class="mobile-submenu-link">Sự kiện</a>
                    </div>
                </li>
                <li class="mobile-nav-item"><a href="HeThongCuaHang.php" class="mobile-nav-link">Hệ thống</a></li>
                <li class="mobile-nav-item"><a href="NhuongQuyen.php" class="mobile-nav-link">Nhượng quyền</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="mobile-nav-item"><a href="DangXuat.php" class="mobile-nav-link" style="color: #d0021b;">Đăng xuất</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="search-overlay">
            <div class="search-container-inner">
                <button class="search-close-btn" id="search-close-btn">
                    <i class="fas fa-times"></i>
                </button>

                <form class="search-form-overlay" action="TimKiem.php" method="GET">
                    <input type="text" name="q" class="search-input-overlay" placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                    <button type="submit" class="search-submit-btn-overlay">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="recommendation-keywords">
                    <h3>Từ khóa nổi bật</h3>
                    <div class="keywords-list">
                        <a href="TimKiem.php?q=Bình giữ nhiệt" class="keyword-tag">Bình giữ nhiệt</a>
                        <a href="TimKiem.php?q=Nồi nhôm" class="keyword-tag">Nồi nhôm</a>
                        <a href="TimKiem.php?q=Hộp đựng" class="keyword-tag">Hộp đựng</a>
                    </div>
                </div>
            </div>
        </div>

    </header>
    
    <main class="main-content-wrapper" id="top"></main>

    <script>
        // Toggle Mobile Menu
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-nav').classList.toggle('active');
        });
        
        function toggleMobileSubmenu(element) {
             event.preventDefault(); 
            element.parentElement.classList.toggle('active');
        }

        // --- XỬ LÝ SEARCH PANEL ---
        const searchOpenBtn = document.getElementById('search-open-btn');
        const searchCloseBtn = document.getElementById('search-close-btn');
        const siteHeader = document.querySelector('.site-header');
        const searchInput = document.querySelector('.search-input-overlay');
        const backdrop = document.getElementById('search-backdrop');

        function openSearch() {
            siteHeader.classList.add('search-active');
            setTimeout(() => { searchInput.focus(); }, 300);
        }

        function closeSearch() {
            siteHeader.classList.remove('search-active');
        }

        searchOpenBtn.addEventListener('click', openSearch);
        searchCloseBtn.addEventListener('click', closeSearch);
        backdrop.addEventListener('click', closeSearch); // Bấm ra ngoài thì đóng
        
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && siteHeader.classList.contains('search-active')) {
                closeSearch();
            }
        });
    </script>