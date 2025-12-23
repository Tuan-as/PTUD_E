<?php
$page_title = "Thanh Toán Chuyển Khoản | LocknLock";
$page_css = "../css/ThanhToan.css";
include 'includes/header.php';
require_once '../db.php';

/* --- 1. KIỂM TRA THAM SỐ --- */
if (!isset($_GET['order_id'])) {
    echo "<script>window.location='TrangChu.php';</script>";
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

/* --- 2. LẤY THÔNG TIN ĐƠN HÀNG (SỬA LẠI LOGIC KHÔNG CẦN CỘT MaNguoiDung) --- */
// Ta dùng JOIN qua bảng DiaChiNhanHang để xác thực quyền sở hữu đơn hàng
$sql = "SELECT dh.* FROM DonHang dh
        JOIN DiaChiNhanHang dc ON dh.MaDiaChiNhanHang = dc.MaDiaChiNhanHang
        WHERE dh.MaDonHang = $order_id 
        AND dc.MaNguoiDung = $user_id 
        AND dh.TrangThai = 'PENDING'";

$order = $conn->query($sql)->fetch_assoc();

if (!$order) {
    echo "<div class='container py-5 text-center'>
            <h3 class='fw-bold'>Đơn hàng không tồn tại hoặc đã hết hạn.</h3>
            <a href='TrangChu.php' class='btn btn-dark mt-3 rounded-0'>VỀ TRANG CHỦ</a>
          </div>";
    include 'includes/footer.php';
    exit;
}

$amount = $order['TongTien'];
$content = "THANHTOAN DH$order_id";
$qr_link = "https://img.vietqr.io/image/MB-0905366982-compact2.jpg?amount=$amount&addInfo=$content&accountName=NGUYEN KIM NGAN";
?>

<style>
    body { background-color: #f8f8f8; }
    .payment-wrapper {
        max-width: 900px; margin: 40px auto; background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 40px;
    }
    .page-heading { text-align: center; margin-bottom: 30px; }
    .page-heading h2 { font-weight: 800; text-transform: uppercase; margin-bottom: 10px; }
    .order-ref { font-size: 1.1em; color: #555; background: #f0f0f0; padding: 5px 15px; font-weight: 600; }
    
    /* Timer */
    .timer-section { text-align: center; margin-bottom: 40px; }
    .timer-circle {
        width: 80px; height: 80px; border: 3px solid #d0021b; color: #d0021b;
        font-size: 24px; font-weight: 800; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 10px; background: #fff;
    }
    .timer-text { font-size: 0.9em; color: #d0021b; font-weight: 600; animation: pulse 2s infinite; }

    /* Grid */
    .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; }
    
    /* QR Box */
    .qr-column { text-align: center; border: 1px solid #eee; padding: 20px; background: #fff; }
    .qr-img { width: 100%; max-width: 280px; display: block; margin: 0 auto; }
    
    /* Info Box */
    .info-column { background: #fcfcfc; border: 1px solid #eee; padding: 30px; }
    .info-title { font-size: 1.1em; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid #111; padding-bottom: 10px; margin-bottom: 20px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #ddd; padding-bottom: 15px; }
    .info-row:last-child { border: none; padding-bottom: 0; }
    .info-value { font-weight: 700; color: #111; text-align: right; }
    .highlight-price { color: #d0021b; font-size: 1.4em; }
    .highlight-content { color: #0056b3; }

    /* Button Copy */
    .btn-copy { background: #eee; border: none; font-size: 0.8em; padding: 2px 8px; margin-left: 8px; cursor: pointer; }
    .btn-copy:hover { background: #333; color: #fff; }

    /* Footer Action */
    .action-area { text-align: center; margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee; }
    .btn-confirm-pay {
        background: #111; color: #fff; border: none; padding: 15px 40px;
        font-size: 1.1em; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.3s;
    }
    .btn-confirm-pay:hover { background: #d0021b; }
    .btn-cancel-order { display: block; margin-top: 15px; color: #999; text-decoration: none; }
    .btn-cancel-order:hover { color: #d0021b; text-decoration: underline; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.6; } 100% { opacity: 1; } }
</style>

<div class="payment-wrapper">
    <div class="page-heading">
        <h2>Thanh Toán Đơn Hàng</h2>
        <span class="order-ref">Mã đơn: #<?php echo $order_id; ?></span>
    </div>

    <div class="timer-section">
        <div class="timer-circle" id="countdown">30</div>
        <div class="timer-text">Đơn hàng sẽ tự động hủy nếu không thanh toán</div>
    </div>

    <div class="payment-grid">
        <div class="qr-column">
            <img src="<?php echo $qr_link; ?>" class="qr-img" alt="QR Code">
            <div class="mt-2 text-muted small"><i class="fas fa-camera"></i> Mở App Ngân hàng để quét mã</div>
        </div>

        <div class="info-column">
            <div class="info-title">Thông Tin Chuyển Khoản</div>
            
            <div class="info-row">
                <span class="text-muted">Ngân hàng</span>
                <span class="info-value">MB Bank (Quân Đội)</span>
            </div>
            
            <div class="info-row">
                <span class="text-muted">Chủ tài khoản</span>
                <span class="info-value">NGUYEN KIM NGAN</span>
            </div>

            <div class="info-row">
                <span class="text-muted">Số tài khoản</span>
                <div class="info-value">
                    0905366982
                    <button class="btn-copy" onclick="copyTxt('0905366982')">COPY</button>
                </div>
            </div>

            <div class="info-row">
                <span class="text-muted">Số tiền</span>
                <div class="info-value highlight-price">
                    <?php echo number_format($amount); ?>₫
                    <button class="btn-copy" onclick="copyTxt('<?php echo $amount; ?>')">COPY</button>
                </div>
            </div>

            <div class="info-row">
                <span class="text-muted">Nội dung</span>
                <div class="info-value highlight-content">
                    <?php echo $content; ?>
                    <button class="btn-copy" onclick="copyTxt('<?php echo $content; ?>')">COPY</button>
                </div>
            </div>
        </div>
    </div>

    <div class="action-area">
        <button class="btn-confirm-pay" onclick="confirmPaid()">
            <i class="fas fa-check-circle me-2"></i> TÔI ĐÃ THANH TOÁN
        </button>
        <a href="#" onclick="cancelOrder()" class="btn-cancel-order">Hủy đơn hàng này</a>
    </div>
</div>

<script>
   const ORDER_ID = <?php echo $order_id; ?>;
   const API_URL = 'fetchdata/process_checkout.php';
   let timeLeft = 30; 

   const timer = setInterval(() => {
       timeLeft--;
       const display = document.getElementById('countdown');
       if(display) display.innerText = timeLeft;

       if(timeLeft <= 10) {
           document.querySelector('.timer-circle').style.borderColor = '#ffcccc';
           document.querySelector('.timer-circle').style.color = 'red';
       }

       if (timeLeft <= 0) {
           clearInterval(timer);
           cancelOrder(true); 
       }
   }, 1000);

   function cancelOrder(isAuto = false) {
       if (!isAuto && !confirm("Bạn chắc chắn muốn hủy đơn hàng này?")) return;
       if (isAuto) alert("Đã hết thời gian thanh toán. Đơn hàng sẽ bị hủy.");

       fetch(API_URL, {
           method: 'POST',
           headers: {'Content-Type': 'application/json'},
           body: JSON.stringify({ action: 'cancel_order', order_id: ORDER_ID })
       })
       .then(r => r.json())
       .then(d => {
           if(d.success) window.location.href = "GioHang.php";
           else alert("Lỗi: " + d.message);
       });
   }

   function confirmPaid() {
       if(confirm("Bạn xác nhận đã chuyển khoản thành công?")) {
           window.location.href = "TT_ThanhCong.php";
       }
   }
   
   function copyTxt(txt) {
       navigator.clipboard.writeText(txt);
       alert("Đã sao chép: " + txt);
   }
</script>

<?php include 'includes/footer.php'; ?>