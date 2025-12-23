<?php
// FILE: store_pages/DangNhap.php
session_start();
require_once '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_id'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Tìm user theo Email hoặc SĐT
    $stmt = $conn->prepare("SELECT * FROM NguoiDung WHERE Email = ? OR SDT = ?");
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // 2. Kiểm tra mật khẩu
        if (password_verify($password, $user['MatKhau'])) {
            
            // 3. KIỂM TRA QUYỀN (CHỈ CHO CUSTOMER)
            if ($user['VaiTro'] === 'CUSTOMER') {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['MaNguoiDung'];
                $_SESSION['user_name'] = $user['Ho'] . ' ' . $user['Ten'];
                $_SESSION['user_role'] = $user['VaiTro'];
                
                header("Location: TrangChu.php");
                exit;
            } else {
                // Nếu là ADMIN hoặc quyền khác -> Chặn
                $error = "Tài khoản quản trị không thể đăng nhập tại đây.";
            }

        } else {
            $error = "Mật khẩu không chính xác.";
        }
    } else {
        $error = "Tài khoản không tồn tại.";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đăng Nhập Khách Hàng | LocknLock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .login-card {
            max-width: 450px; margin: 80px auto; background: #fff;
            padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .btn-dark { background-color: #111; border-color: #111; }
        .btn-dark:hover { background-color: #333; }
        .form-control:focus { box-shadow: none; border-color: #111; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">
        <h3 class="text-center fw-bold mb-4 text-uppercase">Đăng Nhập</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Email hoặc Số điện thoại</label>
                <input type="text" name="login_id" class="form-control" placeholder="Nhập email/SĐT" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2 fw-bold mt-2">ĐĂNG NHẬP</button>
        </form>

        <div class="text-center mt-4 text-muted">
            Bạn chưa có tài khoản? <a href="DangKy.php" class="text-dark fw-bold text-decoration-none">Đăng ký ngay</a>
        </div>
    </div>
</div>

</body>
</html>