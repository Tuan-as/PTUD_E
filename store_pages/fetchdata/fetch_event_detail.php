<?php
// FILE: store_pages/fetchdata/fetch_product_detail.php

// 1. Dọn sạch bộ nhớ đệm để tránh lỗi JSON
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); 

session_start();
require_once '../../db.php'; // Đảm bảo đường dẫn này đúng

header('Content-Type: application/json; charset=utf-8');

function sendResponse($data) {
    ob_end_clean(); // Xóa mọi ký tự lạ trước khi xuất JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$conn) sendResponse(['error' => 'Lỗi kết nối CSDL']);

// =================================================================
// XỬ LÝ POST: GỬI ĐÁNH GIÁ
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        sendResponse(['success' => false, 'error' => 'Vui lòng đăng nhập.']);
    }

    $user_id = $_SESSION['user_id'];
    $spu_id = intval($_POST['spu_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1) sendResponse(['success' => false, 'error' => 'Vui lòng chọn số sao.']);

    // Lấy SKU đại diện
    $checkSku = $conn->query("SELECT MaSKU FROM SKU WHERE MaSPU = $spu_id LIMIT 1");
    if ($checkSku->num_rows == 0) sendResponse(['success' => false, 'error' => 'Sản phẩm lỗi data.']);
    $sku_row = $checkSku->fetch_assoc();
    $sku_id = $sku_row['MaSKU'];

    // Insert Đánh giá
    $stmt = $conn->prepare("INSERT INTO DanhGia (MaNguoiDung, MaSKU, SoDiem, BaiViet, NgayDangTai) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $user_id, $sku_id, $rating, $comment);

    if ($stmt->execute()) {
        $review_id = $stmt->insert_id;
        // Upload ảnh (nếu có)
        if (isset($_FILES['review_img']) && $_FILES['review_img']['error'] == 0) {
            $target_dir = "../../img_vid/reviews/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $ext = strtolower(pathinfo($_FILES['review_img']['name'], PATHINFO_EXTENSION));
            $filename = "rev_" . $review_id . "_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['review_img']['tmp_name'], $target_dir . $filename)) {
                $conn->query("INSERT INTO Media (TenFile, LoaiFile, File) VALUES ('Review', 'image', '$filename')");
                $mid = $conn->insert_id;
                $conn->query("INSERT INTO MediaDanhGia (MaDanhGia, MaMedia) VALUES ($review_id, $mid)");
            }
        }
        sendResponse(['success' => true]);
    } else {
        sendResponse(['success' => false, 'error' => 'Lỗi SQL: ' . $conn->error]);
    }
}

// =================================================================
// XỬ LÝ GET: LẤY CHI TIẾT
// =================================================================
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['spu_id']) ? intval($_GET['spu_id']) : 0);

if ($id <= 0) sendResponse(['error' => 'ID không hợp lệ']);

// 1. Info
$info = $conn->query("SELECT s.*, d.TenDanhMuc FROM SPU s LEFT JOIN DanhMucSanPham d ON s.MaDanhMuc=d.MaDanhMuc WHERE s.MaSPU=$id")->fetch_assoc();
if (!$info) sendResponse(['error' => 'Không tìm thấy sản phẩm']);

// 2. Variants
$skus = [];
$resSku = $conn->query("SELECT * FROM SKU WHERE MaSPU=$id AND TrangThai='ACTIVE'");
while ($sku = $resSku->fetch_assoc()) {
    $sid = $sku['MaSKU'];
    
    // Thuộc tính
    $attrs = [];
    $resAttr = $conn->query("SELECT gt.GiaTri FROM ThuocTinhSanPham tts JOIN GiaTriThuocTinh gt ON tts.MaGiaTri=gt.MaGiaTri JOIN ThuocTinh tt ON gt.MaThuocTinh=tt.MaThuocTinh WHERE tts.MaSKU=$sid ORDER BY tt.SortOrder ASC");
    while($a = $resAttr->fetch_assoc()) $attrs[] = $a['GiaTri'];
    $sku['ThuocTinh'] = !empty($attrs) ? implode(" - ", $attrs) : $sku['Name'];

    // Ảnh
    $images = [];
    $resImg = $conn->query("SELECT m.File FROM Media m JOIN MediaSanPham ms ON m.MaMedia=ms.MaMedia WHERE ms.MaSKU=$sid ORDER BY ms.VaiTro ASC");
    while($img = $resImg->fetch_assoc()) $images[] = $img['File'];
    
    if(empty($images)) {
        $fb = $conn->query("SELECT m.File FROM Media m JOIN MediaSanPham ms ON m.MaMedia=ms.MaMedia JOIN SKU k ON ms.MaSKU=k.MaSKU WHERE k.MaSPU=$id AND ms.VaiTro='bienthe' LIMIT 1")->fetch_assoc();
        if($fb) $images[] = $fb['File'];
    }

    $sku['HinhAnh'] = $images[0] ?? null;
    $sku['Gallery'] = $images;
    $skus[] = $sku;
}

// 3. Reviews
$reviews = [];
$resRev = $conn->query("SELECT dg.*, nd.Ho, nd.Ten, m.File as HinhAnhReview FROM DanhGia dg JOIN NguoiDung nd ON dg.MaNguoiDung=nd.MaNguoiDung LEFT JOIN MediaDanhGia mdg ON dg.MaDanhGia=mdg.MaDanhGia LEFT JOIN Media m ON mdg.MaMedia=m.MaMedia JOIN SKU sk ON dg.MaSKU=sk.MaSKU WHERE sk.MaSPU=$id ORDER BY dg.NgayDangTai DESC");
$totalRating = 0;
while($r = $resRev->fetch_assoc()) {
    $totalRating += $r['SoDiem'];
    $reviews[] = $r;
}
$cnt = count($reviews);
$avg = $cnt > 0 ? round($totalRating/$cnt, 1) : 5;

sendResponse(['info'=>$info, 'variants'=>$skus, 'reviews'=>$reviews, 'avg_rating'=>$avg, 'review_count'=>$cnt]);
?>