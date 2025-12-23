<?php
// FILE: store_pages/fetchdata/fetch_product_detail.php

ob_start();
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

function sendResponse($data) {
    if (ob_get_length()) ob_clean(); 
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // KẾT NỐI DB
    $db_path = __DIR__ . '/../../db.php';
    if (!file_exists($db_path)) throw new Exception("Không tìm thấy file db.php");
    require_once $db_path;

    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($conn->connect_error) throw new Exception("Lỗi kết nối DB: " . $conn->connect_error);

    // =================================================================
    // XỬ LÝ POST: GỬI ĐÁNH GIÁ (CÓ KIỂM TRA ĐÃ MUA HÀNG CHƯA)
    // =================================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) throw new Exception('Vui lòng đăng nhập.');
        
        $uid = $_SESSION['user_id'];
        $spu = intval($_POST['spu_id'] ?? 0);
        $rate = intval($_POST['rating'] ?? 0);
        $cmt = trim($_POST['comment'] ?? '');
        
        if ($rate < 1) throw new Exception('Vui lòng chọn số sao.');
        
        // --- [MỚI THÊM 1] KIỂM TRA: ĐÃ MUA VÀ HOÀN THÀNH ĐƠN HÀNG CHƯA? ---
        // Logic: Tìm trong bảng DonHang + CTDonHang xem có user này + SPU này + trạng thái COMPLETED không
        $checkBuy = $conn->prepare("
            SELECT 1 
            FROM DonHang dh
            JOIN CTDonHang ctdh ON dh.MaDonHang = ctdh.MaDonHang
            JOIN SKU sku ON ctdh.MaSKU = sku.MaSKU
            WHERE dh.MaNguoiDung = ? 
              AND sku.MaSPU = ? 
              AND dh.TrangThai = 'COMPLETED'
            LIMIT 1
        ");
        $checkBuy->bind_param("ii", $uid, $spu);
        $checkBuy->execute();
        $resBuy = $checkBuy->get_result();
        
        if ($resBuy->num_rows == 0) {
            throw new Exception('Bạn chỉ được đánh giá khi đã mua và hoàn thành đơn hàng sản phẩm này.');
        }

        // --- [MỚI THÊM 2] KIỂM TRA: ĐÃ ĐÁNH GIÁ TRƯỚC ĐÓ CHƯA? (CHỐNG SPAM) ---
        $checkReviewed = $conn->prepare("
            SELECT 1 FROM DanhGia dg 
            JOIN SKU sku ON dg.MaSKU = sku.MaSKU
            WHERE dg.MaNguoiDung = ? AND sku.MaSPU = ?
            LIMIT 1
        ");
        $checkReviewed->bind_param("ii", $uid, $spu);
        $checkReviewed->execute();
        if ($checkReviewed->get_result()->num_rows > 0) {
            throw new Exception('Bạn đã đánh giá sản phẩm này rồi.');
        }
        
        // --- NẾU QUA ĐƯỢC 2 CỬA TRÊN THÌ MỚI CHO LƯU ---
        $chk = $conn->query("SELECT MaSKU FROM SKU WHERE MaSPU = $spu LIMIT 1");
        if (!$chk || $chk->num_rows == 0) throw new Exception('SP lỗi data.');
        $sku = $chk->fetch_assoc()['MaSKU'];

        $stmt = $conn->prepare("INSERT INTO DanhGia (MaNguoiDung, MaSKU, SoDiem, BaiViet, NgayDangTai) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $uid, $sku, $rate, $cmt);
        
        if ($stmt->execute()) {
            $review_id = $stmt->insert_id;
            // Xử lý ảnh review (nếu có)
            if (isset($_FILES['review_img']) && $_FILES['review_img']['error'] == 0) {
                $target_dir = "../../img_vid/reviews/";
                if (!file_exists($target_dir)) @mkdir($target_dir, 0777, true);
                $ext = strtolower(pathinfo($_FILES['review_img']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $fn = "review_" . $review_id . "_" . time() . "." . $ext;
                    if (@move_uploaded_file($_FILES['review_img']['tmp_name'], $target_dir . $fn)) {
                        $conn->query("INSERT INTO Media (TenFile, LoaiFile, File) VALUES ('Review Img', 'image', '$fn')");
                        $mid = $conn->insert_id;
                        $conn->query("INSERT INTO MediaDanhGia (MaDanhGia, MaMedia) VALUES ($review_id, $mid)");
                    }
                }
            }
            sendResponse(['success' => true]);
        } else {
            throw new Exception('Lỗi SQL: ' . $stmt->error);
        }
    }

    // =================================================================
    // XỬ LÝ GET: LẤY CHI TIẾT SẢN PHẨM (GIỮ NGUYÊN)
    // =================================================================
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) throw new Exception('ID SP không hợp lệ.');

    $resInfo = $conn->query("SELECT s.*, d.TenDanhMuc FROM SPU s LEFT JOIN DanhMucSanPham d ON s.MaDanhMuc = d.MaDanhMuc WHERE s.MaSPU = $id");
    if (!$resInfo || $resInfo->num_rows == 0) throw new Exception('Sản phẩm không tồn tại.');
    $info = $resInfo->fetch_assoc();

    $skus = [];
    $firstSku = 0;
    $resSku = $conn->query("SELECT * FROM SKU WHERE MaSPU = $id AND TrangThai='ACTIVE'");
    if ($resSku) {
        while ($r = $resSku->fetch_assoc()) {
            if ($firstSku == 0) $firstSku = $r['MaSKU'];
            $sid = $r['MaSKU'];
            
            $attrs = [];
            $resA = $conn->query("SELECT gt.GiaTri FROM ThuocTinhSanPham tts JOIN GiaTriThuocTinh gt ON tts.MaGiaTri=gt.MaGiaTri JOIN ThuocTinh tt ON gt.MaThuocTinh=tt.MaThuocTinh WHERE tts.MaSKU=$sid ORDER BY tt.SortOrder ASC");
            while ($a = $resA->fetch_assoc()) $attrs[] = $a['GiaTri'];
            $r['ThuocTinh'] = !empty($attrs) ? implode(" - ", $attrs) : $r['Name'];

            $imgs = [];
            $resI = $conn->query("SELECT m.File FROM Media m JOIN MediaSanPham ms ON m.MaMedia=ms.MaMedia WHERE ms.MaSKU=$sid");
            while ($i = $resI->fetch_assoc()) $imgs[] = $i['File'];
            $r['HinhAnh'] = $imgs[0] ?? null;
            $r['Gallery'] = $imgs;
            $skus[] = $r;
        }
    }

    $liked = false;
    try {
        if (isset($_SESSION['user_id']) && $firstSku > 0) {
            $uid = $_SESSION['user_id'];
            $conn->query("INSERT IGNORE INTO LichSuHoatDong (MaNguoiDung, MaSKU, ThoiDiem) VALUES ($uid, $firstSku, NOW())");
            $chkLike = $conn->query("SELECT 1 FROM DanhSachYeuThich ds JOIN SKU sk ON ds.MaSKU=sk.MaSKU WHERE sk.MaSPU=$id AND ds.MaNguoiDung=$uid LIMIT 1");
            if ($chkLike && $chkLike->num_rows > 0) $liked = true;
        }
    } catch (Exception $e) {}

    $reviews = [];
    $sum = 0;
    $resR = $conn->query("SELECT dg.*, nd.Ho, nd.Ten, m.File as HinhAnhReview FROM DanhGia dg JOIN NguoiDung nd ON dg.MaNguoiDung=nd.MaNguoiDung LEFT JOIN MediaDanhGia mdg ON dg.MaDanhGia=mdg.MaDanhGia LEFT JOIN Media m ON mdg.MaMedia=m.MaMedia JOIN SKU sk ON dg.MaSKU=sk.MaSKU WHERE sk.MaSPU=$id ORDER BY dg.NgayDangTai DESC");
    if ($resR) {
        while ($rv = $resR->fetch_assoc()) {
            $sum += $rv['SoDiem'];
            $reviews[] = $rv;
        }
    }
    $cnt = count($reviews);
    $avg = $cnt > 0 ? round($sum / $cnt, 1) : 5;

    sendResponse([
        'info' => $info,
        'variants' => $skus,
        'reviews' => $reviews,
        'review_count' => $cnt,
        'avg_rating' => $avg,
        'is_liked' => $liked
    ]);

} catch (Exception $e) {
    sendResponse(['error' => $e->getMessage()]);
}
?>