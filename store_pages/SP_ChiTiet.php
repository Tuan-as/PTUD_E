<?php 
$page_title = "Chi Tiết Sản Phẩm | LocknLock";
$page_css = "../css/SP_ChiTiet.css"; 
include 'includes/header.php';
?>

<div class="container detail-container">
    
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="TrangChu.php">Trang chủ</a></li>
        <li class="sep">/</li>
        <li class="breadcrumb-item"><a href="SanPham.php">Sản phẩm</a></li>
        <li class="sep">/</li>
        <li class="breadcrumb-item active" id="bread-name">Đang tải...</li>
    </ul>

    <div class="main-grid">
        <div class="left-col">
            <div class="main-img-box">
                <button class="wishlist-icon"><i class="far fa-heart"></i></button>
                <img src="../img_vid/no-image.png" id="main-img" alt="Product">
            </div>
            <div id="thumb-list" class="thumb-list d-flex mt-2"></div>
        </div>

        <div class="right-col">
            <span class="cat-tag">LOCKNLOCK</span>
            <h1 class="p-title">Đang tải...</h1>

            <div class="rating-box">
                <span class="stars">★★★★★</span>
                <span class="text-muted small">(<span id="rev-count">0</span> đánh giá)</span>
            </div>

            <div class="p-price">--- ₫</div>

            <div class="mb-4">
                <label class="d-block fw-bold mb-2">Phân loại:</label>
                <div class="variants-box">Loading...</div>
            </div>

            <div class="actions">
                <div class="qty-control">
                    <button onclick="updateQty(-1)">-</button>
                    <input type="text" id="qty" value="1" readonly>
                    <button onclick="updateQty(1)">+</button>
                </div>
                <button class="btn-cart">THÊM VÀO GIỎ HÀNG</button>
            </div>
            
            <div class="stock-status small mb-3"></div>

            <div style="background:#f8f9fa; padding:15px; border:1px dashed #ddd; font-size:0.9em; color:#666;">
                <p class="m-1"><i class="fas fa-truck me-2"></i> Freeship đơn từ 1.000.000đ</p>
                <p class="m-1"><i class="fas fa-shield-alt me-2"></i> Bảo hành chính hãng 12 tháng</p>
            </div>
        </div>
    </div>

    <div class="product-tabs-section">
        <div class="tabs-header">
            <button class="tab-link active" onclick="openTab('desc')">Mô tả sản phẩm</button>
            <button class="tab-link" onclick="openTab('review')">Đánh giá khách hàng</button>
        </div>

        <div id="tab-desc" class="tab-content" style="display:block;">
            <div id="desc-content">Đang cập nhật...</div>
        </div>

        <div id="tab-review" class="tab-content" style="display:none;">
            <div class="review-wrapper">
                <div class="review-form">
                    <h4>GỬI ĐÁNH GIÁ</h4>
                    <form id="form-review" enctype="multipart/form-data">
                        <div class="star-rating">
                            <input type="radio" id="s5" name="rating" value="5"><label for="s5">★</label>
                            <input type="radio" id="s4" name="rating" value="4"><label for="s4">★</label>
                            <input type="radio" id="s3" name="rating" value="3"><label for="s3">★</label>
                            <input type="radio" id="s2" name="rating" value="2"><label for="s2">★</label>
                            <input type="radio" id="s1" name="rating" value="1"><label for="s1">★</label>
                        </div>
                        <textarea name="comment" placeholder="Mời bạn chia sẻ cảm nhận (Bắt buộc chọn sao)..."></textarea>
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="file" name="review_img" accept="image/*" style="font-size:0.9em;">
                            <button type="submit" class="btn-submit-review">Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
                <div id="review-list"></div>
            </div>
        </div>
    </div>
</div>

<script src="../js/SP_ChiTiet.js"></script>
<?php include 'includes/footer.php'; ?>