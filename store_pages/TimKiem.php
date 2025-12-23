<?php
// --- 1. CẤU HÌNH & KẾT NỐI ---
$page_title = "Kết quả tìm kiếm | LocknLock";

if (file_exists('../db.php')) {
    require '../db.php';
} else {
    die("Lỗi: Không tìm thấy file db.php. Hãy kiểm tra cấu trúc thư mục.");
}

// --- 2. LẤY TỪ KHÓA ---
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$products = [];

// --- 3. XỬ LÝ TÌM KIẾM 
if ($keyword !== '') {
    $sql = "SELECT * FROM SPU WHERE TenSanPham LIKE ? ORDER BY MaSPU DESC";
    
    if ($stmt = $conn->prepare($sql)) {
        $searchTerm = "%" . $keyword . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $maSPU = $row['MaSPU'];
            
            // Mặc định
            $row['price'] = 0; 
            $row['sale_price'] = 0;
            $row['image'] = 'default.png'; 

            // Lấy SKU (Giá)
            $sku_sql = "SELECT MaSKU, GiaGoc, GiaGiam 
                        FROM SKU 
                        WHERE MaSPU = '$maSPU' AND TrangThai = 'ACTIVE' 
                        LIMIT 1";
            $sku_res = $conn->query($sku_sql);
            
            if ($sku_res && $sku_res->num_rows > 0) {
                $sku_data = $sku_res->fetch_assoc();
                $maSKU = $sku_data['MaSKU'];
                
                $row['price'] = $sku_data['GiaGoc'];
                $row['sale_price'] = $sku_data['GiaGiam'];

                // Lấy Ảnh
                $img_sql = "SELECT m.File 
                            FROM Media m 
                            JOIN MediaSanPham msp ON m.MaMedia = msp.MaMedia 
                            WHERE msp.MaSKU = '$maSKU' 
                            LIMIT 1";
                
                $img_res = $conn->query($img_sql);
                if ($img_res && $img_res->num_rows > 0) {
                    $img_data = $img_res->fetch_assoc();
                    if (!empty($img_data['File'])) {
                        $row['image'] = $img_data['File'];
                    }
                }
            }
            $products[] = $row;
        }
        $stmt->close();
    }
}

// --- 4. INCLUDE HEADER ---
include 'includes/header.php'; 
?>

<style>
    /* KHỐI CHỨA SẢN PHẨM */
    .product-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; font-family: 'Arial', sans-serif; min-height: 60vh; }
    .page-title { font-size: 1.6em; border-bottom: 2px solid #eee; margin-bottom: 30px; padding-bottom: 10px; color: #000; }
    
    /* STYLE RIÊNG CHO TỪ KHÓA TÌM KIẾM */
    .search-keyword { color: #d0021b; }
    .search-count { font-size: 0.7em; color: #777; font-weight: normal; margin-left: 10px; }

    /* LƯỚI SẢN PHẨM (GRID) */
    .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; min-height: 300px;}

    .product-card { 
        border: 1px solid #eee; text-decoration: none; color: #333; 
        display: flex; flex-direction: column; transition: 0.3s; 
        background: white; overflow: hidden; position: relative;
    }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* ẢNH SẢN PHẨM */
    .card-img { padding-top: 100%; position: relative; background: #fafafa; }
    .card-img img { 
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
        object-fit: contain; padding: 15px; transition: 0.5s; 
    }
    .product-card:hover img { transform: scale(1.05); }

    /* NHÃN GIẢM GIÁ */
    .badge-sale { 
        position: absolute; top: 10px; right: 10px; 
        background: #d0021b; color: white; font-size: 10px; 
        font-weight: bold; padding: 4px 8px; z-index: 2;
    }

    /* THÔNG TIN SẢN PHẨM */
    .card-body { padding: 15px; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between; }
    .card-cat { font-size: 0.75em; color: #999; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px; }
    .card-title { 
        font-size: 1em; margin: 0 0 10px; font-weight: 600; line-height: 1.4; 
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em; 
        color: #333; /* Đảm bảo màu chữ đen */
    }

    /* GIÁ */
    .price-box { display: flex; flex-direction: column; align-items: flex-start; }
    .old-price { text-decoration: line-through; color: #999; font-size: 0.9em; margin-bottom: 2px; }
    .sale-price { color: #d0021b; font-weight: bold; font-size: 1.1em; }

    /* TỒN KHO */
    .stock { font-size: 0.8em; margin-top: 8px; display: block; }
    .stock.in { color: #28a745; } 
    .stock.out { color: #dc3545; }
    
    /* EMPTY STATE */
    .empty-search { text-align: center; padding: 50px 0; color: #666; width: 100%; grid-column: 1 / -1; }
    .btn-back { display: inline-block; margin-top: 20px; padding: 10px 30px; background: #333; color: #fff; text-decoration: none; transition: 0.3s; }
    .btn-back:hover { background: #d0021b; }

    @media(max-width: 992px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
    @media(max-width: 600px) { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }
</style>

<main class="main-content">
    <div class="product-container">
        
        <h1 class="page-title">
            <?php if ($keyword == ''): ?>
                Bạn chưa nhập từ khóa
            <?php else: ?>
                Kết quả cho: <span class="search-keyword">"<?php echo htmlspecialchars($keyword); ?>"</span>
                <span class="search-count">(<?php echo count($products); ?> sản phẩm)</span>
            <?php endif; ?>
        </h1>

        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    
                    <a href="SP_ChiTiet.php?id=<?php echo $p['MaSPU']; ?>" class="product-card">
                        
                        <div class="card-img">
                            <?php 
                                // Xử lý đường dẫn ảnh
                                $img_name = basename($p['image']);
                                $path_products = "../img_vid/img_products/" . $img_name;
                                $path_vid = "../img_vid/" . $img_name;
                                
                                if (file_exists($path_products)) {
                                    $final_img = $path_products;
                                } elseif (file_exists($path_vid)) {
                                    $final_img = $path_vid;
                                } else {
                                    $final_img = $path_products; 
                                }
                            ?>
                            <img src="<?php echo htmlspecialchars($final_img); ?>" 
                                 alt="<?php echo htmlspecialchars($p['TenSanPham']); ?>"
                                 onerror="this.src='../img_vid/default.png'">
                            
                            <?php if ($p['sale_price'] > 0 && $p['sale_price'] < $p['price']): ?>
                                <span class="badge-sale">SALE</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body">
                            <span class="card-cat">LOCKNLOCK</span>
                            
                            <h3 class="card-title" title="<?php echo htmlspecialchars($p['TenSanPham']); ?>">
                                <?php echo htmlspecialchars($p['TenSanPham']); ?>
                            </h3>
                            
                            <div class="price-box">
                                <?php if ($p['sale_price'] > 0 && $p['sale_price'] < $p['price']): ?>
                                    <span class="old-price"><?php echo number_format($p['price'], 0, ',', '.'); ?>đ</span>
                                    <span class="sale-price"><?php echo number_format($p['sale_price'], 0, ',', '.'); ?>đ</span>
                                <?php elseif ($p['price'] > 0): ?>
                                    <span class="sale-price"><?php echo number_format($p['price'], 0, ',', '.'); ?>đ</span>
                                <?php else: ?>
                                    <span class="sale-price" style="font-size: 0.9em; color:#666;">Liên hệ</span>
                                <?php endif; ?>
                            </div>

                            <span class="stock in"><i class="fas fa-check-circle"></i> Còn hàng</span>
                        </div>
                    </a>

                <?php endforeach; ?>
            <?php else: ?>
                
                <div class="empty-search">
                    <i class="fas fa-search" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
                    <p>Không tìm thấy sản phẩm nào khớp với từ khóa "<strong><?php echo htmlspecialchars($keyword); ?></strong>".</p>
                    <a href="SanPham.php?category=all" class="btn-back">Xem tất cả sản phẩm</a>
                </div>

            <?php endif; ?>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>