<?php
/* =======================
   KẾT NỐI DATABASE
======================= */
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=locknlock_store;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

/* =======================
   KPI TỔNG QUAN
======================= */

$totalOrders = (int)$pdo->query("
    SELECT COUNT(*) FROM DonHang
")->fetchColumn();

$totalRevenue = (float)$pdo->query("
    SELECT COALESCE(SUM(TongTien),0)
    FROM DonHang
    WHERE TrangThai = 'COMPLETED'
")->fetchColumn();

$totalUsers = (int)$pdo->query("
    SELECT COUNT(*) 
    FROM NguoiDung
    WHERE VaiTro = 'CUSTOMER'
")->fetchColumn();

$totalProducts = (int)$pdo->query("
    SELECT COUNT(*) FROM SPU
")->fetchColumn();

$orderwaitlist = (int)$pdo->query("
    SELECT COUNT(*) 
    FROM DonHang 
    WHERE TrangThai = 'PENDING'
")->fetchColumn();

$numcanceledorder = (int)$pdo->query("
    SELECT COUNT(*) 
    FROM DonHang 
    WHERE TrangThai = 'CANCELED'
")->fetchColumn();
$revenueChart = $pdo->query("
    SELECT 
        DATE(NgayDat) AS ngay,
        SUM(TongTien) AS doanhthu
    FROM DonHang
    WHERE TrangThai = 'COMPLETED'
    GROUP BY DATE(NgayDat)
    ORDER BY DATE(NgayDat)
    LIMIT 7
")->fetchAll(PDO::FETCH_ASSOC);

/* =======================
   ADMIN ĐANG ĐĂNG NHẬP
======================= */
$userName = $pdo->query("
    SELECT CONCAT(Ho,' ',Ten)
    FROM NguoiDung
    WHERE VaiTro = 'ADMIN'
    ORDER BY MaNguoiDung ASC
")->fetchColumn();

/* =======================
   THUỘC TÍNH SẢN PHẨM
======================= */
$attributes = [];

$stmt = $pdo->query("
    SELECT 
        tt.TenThuocTinh,
        gt.GiaTri
    FROM ThuocTinh tt
    JOIN GiaTriThuocTinh gt 
        ON tt.MaThuocTinh = gt.MaThuocTinh
    ORDER BY tt.SortOrder, gt.SortOrder
");

while ($row = $stmt->fetch()) {
    $attributes[$row['TenThuocTinh']][] = $row['GiaTri'];
}

$attributes = array_map(
    fn($name, $values) => [
        'name' => $name,
        'values' => $values
    ],
    array_keys($attributes),
    $attributes
);

/* =======================
   KHÁCH HÀNG
======================= */
$customers = $pdo->query("
    SELECT 
        nd.MaNguoiDung AS id,
        CONCAT(nd.Ho,' ',nd.Ten) AS name,
        nd.Email,
        nd.SDT AS phone,
        DATE(nd.NgayTao) AS register_date,
        COUNT(dh.MaDonHang) AS total_orders,
        COALESCE(SUM(dh.TongTien),0) AS total_spent
    FROM NguoiDung nd
    LEFT JOIN DonHang dh 
        ON nd.MaNguoiDung = dh.MaNguoiDung
    WHERE nd.VaiTro = 'CUSTOMER'
    GROUP BY 
        nd.MaNguoiDung,
        nd.Ho,
        nd.Ten,
        nd.Email,
        nd.SDT,
        nd.NgayTao
    ORDER BY nd.NgayTao DESC
")->fetchAll();

/* =======================
   MÃ GIẢM GIÁ
======================= */
$promotions = $pdo->query("
    SELECT
        mg.MaGiamGia,
        mg.CodeGiamGia AS name,
        mg.LoaiGiamGia AS type,
        mg.MucGiamGia AS value,
        mg.NgayBatDau AS start_date,
        mg.NgayHetHan AS end_date,
        mg.TrangThai AS status,
        COUNT(ls.MaNguoiDung) AS usage_count
    FROM MaGiamGia mg
    LEFT JOIN LichSuDungMaGiamGia ls
        ON mg.MaGiamGia = ls.MaGiamGia
    GROUP BY
        mg.MaGiamGia,
        mg.CodeGiamGia,
        mg.LoaiGiamGia,
        mg.MucGiamGia,
        mg.NgayBatDau,
        mg.NgayHetHan,
        mg.TrangThai
    ORDER BY mg.NgayBatDau DESC
")->fetchAll();

/* =======================
   LỊCH SỬ TỒN KHO
======================= */
$inventoryLogs = $pdo->query("
    SELECT
        l.MaThayDoiTonKho AS code,
        l.NgayThucHien AS date,
        l.LoaiThayDoi AS type,
        s.Name AS product,
        l.SoLuong AS quantity,
        s.TonKho AS after_stock,
        l.GhiChu AS note,
        CONCAT(nd.Ho,' ',nd.Ten) AS staff
    FROM LichSuThayDoiTonKho l
    JOIN SKU s 
        ON l.MaSKU = s.MaSKU
    LEFT JOIN NguoiDung nd 
        ON l.NguoiThucHien = nd.MaNguoiDung
    ORDER BY l.NgayThucHien DESC
    LIMIT 10
")->fetchAll();

/* =======================
   ĐƠN HÀNG GẦN NHẤT
======================= */
$orders = $pdo->query("
    SELECT
        dh.MaDonHang AS code,
        CONCAT(kh.Ho,' ',kh.Ten) AS customer,
        dh.TongTien AS total,
        dh.TrangThai AS status,
        CONCAT(nv.Ho,' ',nv.Ten) AS staff
    FROM DonHang dh
    LEFT JOIN NguoiDung kh 
        ON dh.MaNguoiDung = kh.MaNguoiDung
    LEFT JOIN NguoiDung nv 
        ON dh.NguoiPhuTrach = nv.MaNguoiDung
    ORDER BY dh.NgayDat DESC
    LIMIT 5
")->fetchAll();

/* =======================
   SẢN PHẨM
======================= */
$products = $pdo->query("
    SELECT
        spu.MaSPU,
        spu.TenSanPham AS name,
        MIN(sku.GiaGoc) AS price,
        SUM(sku.TonKho) AS stock,
        CASE 
            WHEN SUM(sku.TonKho) = 0 THEN 'OUT_OF_STOCK'
            WHEN SUM(sku.TonKho) <= 20 THEN 'LOW_STOCK'
            ELSE 'ACTIVE'
        END AS status
    FROM SPU spu
    JOIN SKU sku 
        ON spu.MaSPU = sku.MaSPU
    GROUP BY 
        spu.MaSPU,
        spu.TenSanPham
")->fetchAll();

/* =======================
   DANH MỤC SẢN PHẨM
======================= */
$categories = $pdo->query("
    SELECT 
        dm.MaDanhMuc,
        dm.TenDanhMuc,
        dm.MoTa AS MoTa,
        COUNT(spu.MaSPU) AS so_san_pham
    FROM DanhMucSanPham dm
    LEFT JOIN SPU spu 
        ON dm.MaDanhMuc = spu.MaDanhMuc
    GROUP BY 
        dm.MaDanhMuc,
        dm.TenDanhMuc,
        dm.MoTa
    ORDER BY dm.MaDanhMuc ASC
")->fetchAll();




// ===== LẤY SPU =====
$products = $pdo->query("
  SELECT
    s.MaSPU,
    s.TenSanPham,
    s.MoTaNgan,
    s.MoTaDai,
    s.NgayTao,
    dm.TenDanhMuc
  FROM SPU s
  LEFT JOIN DanhMucSanPham dm 
    ON s.MaDanhMuc = dm.MaDanhMuc
  ORDER BY s.NgayTao DESC
")->fetchAll();




// ===== LẤY SKU =====
$skusBySPU = [];
$skus = $pdo->query("SELECT * FROM SKU ORDER BY MaSPU")->fetchAll();
foreach ($skus as $sku) {
$skusBySPU[$sku['MaSPU']][] = $sku;
}
/* =======================
   QUẢN LÝ ĐƠN HÀNG (Admin_DonHang.php)
======================= */
$searchCode = $_GET['search_code'] ?? '';
$searchCustomer = $_GET['search_customer'] ?? '';
$filterStatus = $_GET['filter_status'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

$sqlOrders = "SELECT dh.*, CONCAT(kh.Ho, ' ', kh.Ten) as customer_name 
              FROM DonHang dh 
              LEFT JOIN NguoiDung kh ON dh.MaNguoiDung = kh.MaNguoiDung 
              WHERE 1=1";

$paramsOrders = [];

if (!empty($searchCode)) {
    $sqlOrders .= " AND dh.MaDonHang = ?";
    $paramsOrders[] = $searchCode;
}
if (!empty($searchCustomer)) {
    $sqlOrders .= " AND CONCAT(kh.Ho, ' ', kh.Ten) LIKE ?";
    $paramsOrders[] = '%' . $searchCustomer . '%';
}
if (!empty($filterStatus)) {
    $sqlOrders .= " AND dh.TrangThai = ?";
    $paramsOrders[] = $filterStatus;
}
if (!empty($fromDate)) {
    $sqlOrders .= " AND dh.NgayDat >= ?";
    $paramsOrders[] = $fromDate . ' 00:00:00';
}
if (!empty($toDate)) {
    $sqlOrders .= " AND dh.NgayDat <= ?";
    $paramsOrders[] = $toDate . ' 23:59:59';
}

$sqlOrders .= " ORDER BY dh.NgayDat DESC";

$stmtOrders = $pdo->prepare($sqlOrders);
$stmtOrders->execute($paramsOrders);
$listOrders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

