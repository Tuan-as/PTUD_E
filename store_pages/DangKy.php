<?php
// FILE: store_pages/DangKy.php
session_start();
require_once '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu
    $ho = trim($_POST['ho']);
    $ten = trim($_POST['ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Validate cơ bản
    if ($pass !== $confirm_pass) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        // 2. Kiểm tra Email hoặc SĐT đã tồn tại chưa
        $check = $conn->prepare("SELECT MaNguoiDung FROM NguoiDung WHERE Email = ? OR SDT = ?");
        $check->bind_param("ss", $email, $sdt);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Email hoặc Số điện thoại này đã được sử dụng.";
        } else {
            // 3. Đăng ký thành công
            // Mã hóa mật khẩu
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            
            // QUAN TRỌNG: Luôn set VaiTro = 'CUSTOMER'
            $role = 'CUSTOMER'; 

            $stmt = $conn->prepare("INSERT INTO NguoiDung (Ho, Ten, Email, SDT, MatKhau, VaiTro, NgayTao) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $ho, $ten, $email, $sdt, $hashed_pass, $role);
            
            if ($stmt->execute()) {
                echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location='DangNhap.php';</script>";
                exit;
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đăng Ký Khách Hàng | LocknLock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .register-card {
            max-width: 600px; margin: 50px auto; background: #fff;
            padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .btn-dark { background-color: #111; border-color: #111; }
        .btn-dark:hover { background-color: #333; }
        .form-control:focus { box-shadow: none; border-color: #111; }
    </style>
</head>
<body>

<div class="container">
    <div class="register-card">
        <h3 class="text-center fw-bold mb-4 text-uppercase">Đăng Ký Tài Khoản</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Họ</label>
                    <input type="text" name="ho" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tên</label>
                    <input type="text" name="ten" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Số điện thoại</label>
                <input type="text" name="sdt" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2 fw-bold mt-3">ĐĂNG KÝ</button>
        </form>

        <div class="text-center mt-4 text-muted">
            Đã có tài khoản? <a href="DangNhap.php" class="text-dark fw-bold text-decoration-none">Đăng nhập</a>
        </div>
    </div>
</div>

</body>
</html>