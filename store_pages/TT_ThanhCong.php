<?php
$page_title = "Đặt Hàng Thành Công";
include 'includes/header.php';
?>

<style>
    .success-container {
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 50px 0;
    }
    
    /* Icon thành công */
    .icon-check {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }

    /* Style chung cho nút vuông */
    .btn-square {
        display: inline-block;
        padding: 15px 30px;
        margin: 10px;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid #000; /* Viền đen */
        border-radius: 0;       /* Vuông góc 100% */
        transition: all 0.3s ease;
        min-width: 220px;       /* Chiều rộng cố định cho đẹp */
    }

    /* Nút Tiếp tục mua sắm (Nền trắng, chữ đen) */
    .btn-continue {
        background-color: #fff;
        color: #000;
    }
    .btn-continue:hover {
        background-color: #000;
        color: #fff;
    }

    /* Nút Xem đơn hàng (Nền đen, chữ trắng) */
    .btn-view-order {
        background-color: #000;
        color: #fff;
    }
    .btn-view-order:hover {
        background-color: #333; /* Đen nhạt hơn xíu khi hover */
        border-color: #333;
    }
</style>

<div class="container success-container">
    <i class="fas fa-check-circle icon-check"></i>
    
    <h2 class="fw-bold text-uppercase">ĐẶT HÀNG THÀNH CÔNG!</h2>
    <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại LocknLock. Đơn hàng của bạn đang được xử lý.</p>
    
    <div>
        <a href="SanPham.php" class="btn-square btn-continue">Tiếp tục mua sắm</a>
        <a href="TaiKhoanCuaToi.php" class="btn-square btn-view-order">Xem đơn hàng</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>