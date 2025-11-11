<?php 
    /* * File này sẽ được include ở đầu mỗi trang.
     * Cần định nghĩa 2 biến NÀY TRƯỚC KHI include:
     * * $page_title: Tiêu đề của trang (ví dụ: "Trang Chủ | LocknLock")
     * $page_css: (Tùy chọn) Link tới file CSS riêng của trang
     * (ví dụ: $page_css = "../css/TrangChu.css";)
     */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo isset($page_title) ? $page_title : "LocknLock"; ?></title>
    
    <link rel="stylesheet" href="../css/style.css"> 
    
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header class="site-header">
        
        <div class="nav-bar">
            <div class="logo-container">
                <a href="TrangChu.php" class="logo-link">
                    LocknLock
                </a>
            </div>

            <nav class="main-nav">
                <ul class="nav-list">
                    
                    <li class="nav-item has-submenu">
                        <a href="#">Giới thiệu</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Thương hiệu</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Lịch sử hình thành</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Giải thưởng</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Thông tin doanh nghiệp</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Trách nhiệm xã hội</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item has-submenu submenu-full">
                        <a href="#">Sản phẩm</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column">
                                    <div class="submenu-title">Bình nước</div>
                                    <ul class="submenu-links">
                                        <li><a href="#">Bình giữ nhiệt</a></li>
                                        <li><a href="#">Bình nước nhựa</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <div class="submenu-title">Đồ dùng nhà bếp</div>
                                    <ul class="submenu-links">
                                        <li><a href="#">Dụng Cụ Nấu Ăn</a></li>
                                        <li><a href="#">Nồi & chảo</a></li>
                                        <li><a href="#">Phụ kiện nhà bếp</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <div class="submenu-title">Hộp đựng thực phẩm</div>
                                    <ul class="submenu-links">
                                        <li><a href="#">Hộp Bảo Quản</a></li>
                                        <li><a href="#">Hộp đựng gia vị/ dầu</a></li>
                                        <li><a href="#">Hộp Cơm</a></li>
                                        <li><a href="#">Hộp Lò Nướng & Lò Vi Sóng</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <div class="submenu-title">Đồ dùng sinh hoạt</div>
                                    <ul class="submenu-links">
                                        <li><a href="#">Đồ dùng nhà tắm</a></li>
                                        <li><a href="#">Dụng cụ Vệ sinh & Giặt</a></li>
                                        <li><a href="#">Ngoài trời & Du lịch</a></li>
                                        <li><a href="#">Hộp đựng & Dụng cụ sắp xếp</a></li>
                                    </ul>
                                </div>
                                <div class="submenu-column">
                                    <div class="submenu-title">Điện Gia Dụng</div>
                                    <ul class="submenu-links">
                                        <li><a href="#">Làm đẹp & Sức khỏe</a></li>
                                        <li><a href="#">Thiết bị gia dụng</a></li>
                                        <li><a href="#">Thiết bị nhà bếp</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item has-submenu">
                        <a href="#">Đổi mới</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">LL labs</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="#" class="submenu-title">Design center</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item has-submenu">
                        <a href="CauChuyen.php">Câu chuyện</a>
                        <div class="submenu-container">
                            <div class="submenu-content">
                                <div class="submenu-column">
                                    <a href="CC_TinTuc.php" class="submenu-title">Tin tức</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="CC_Lounge.php" class="submenu-title">Lounge</a>
                                </div>
                                <div class="submenu-column">
                                    <a href="CC_SuKien.php" class="submenu-title">Sự kiện</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item"><a href="#">Hệ thống</a></li>
                    <li class="nav-item"><a href="#">Nhượng quyền</a></li>
                </ul>
            </nav>

            <div class="header-controls">
                <a href="#" class="btn btn-login">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Đăng nhập
                </a>
                <a href="#" class="btn-register">Đăng ký</a>
                
                <a href="#" class="icon-btn cart-btn">
                     <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
                
                <button class="icon-btn search-open-btn" id="search-open-btn">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </div>
        <div class="search-overlay">
            <div class="logo-container">
                <a href="TrangChu.php" class="logo-link">
                    LocknLock
                </a>
            </div>
            
            <form class="search-form">
                <input type="text" class="search-input" placeholder="Nhập mã HOTDEALT1 giảm ngay 25%">
                <button type="submit" class="icon-btn search-submit-btn">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>

            <button class="icon-btn search-close-btn" id="search-close-btn">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            <div class="recommendation-keywords">
                <h3>Recommendation keywords</h3>
                <div class="keywords-list">
                    <span>Bình giữ nhiệt</span>
                    <span>Hộp cơm</span>
                    <span>Nồi cơm điện</span>
                    <span>Tăm nước</span>
                </div>
            </div>
        </div>
        </header>
    <main class="main-content-wrapper" id="top"></main>