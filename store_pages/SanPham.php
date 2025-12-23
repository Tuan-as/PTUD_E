<?php 
    // Định nghĩa thông tin trang
    $page_title = "Sản Phẩm | LocknLock";
    
    // Đường dẫn CSS (Từ store_pages lùi ra root -> vào css)
    $page_css = "../css/SanPham.css"; 
    
    // Đường dẫn JS (Từ store_pages lùi ra root -> vào js)
    $page_js = "../js/SanPham.js"; 
    
    // Include Header
    include 'includes/header.php'; 
?>

<div class="product-container">
    <h1 class="page-title">SẢN PHẨM</h1>
    
    <div class="toolbar-wrapper">
        <div class="category-tabs">
            <button class="cat-btn active" data-id="all">Tất cả</button>
            <button class="cat-btn" data-id="1">Bình nước</button>
            <button class="cat-btn" data-id="2">Đồ dùng nhà bếp</button>
            <button class="cat-btn" data-id="3">Hộp đựng thực phẩm</button>
            <button class="cat-btn" data-id="4">Đồ dùng sinh hoạt</button>
            <button class="cat-btn" data-id="5">Điện Gia Dụng</button>
        </div>
        
        <div class="filter-bar">
            <div class="filter-group">
                <label>Khoảng giá:</label>
                <input type="number" id="price-min" placeholder="0"> - 
                <input type="number" id="price-max" placeholder="Tối đa">
                <button id="btn-apply-filter">Lọc</button>
            </div>
            <div class="filter-group">
                <select id="sort-select">
                    <option value="newest">Mới nhất</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                </select>
            </div>
        </div>
    </div>

    <div id="product-grid" class="product-grid">
        <div class="loading">Đang tải sản phẩm...</div>
    </div>
</div>

<?php 
    // Include Footer
    include 'includes/footer.php'; 
?>