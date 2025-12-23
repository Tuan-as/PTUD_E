<?php
// FILE: Admin_pages/Admin_DangNhap.php
session_start();

require_once 'includes/db.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        try {
            // 1. Tìm user theo Email hoặc SĐT (Dùng $pdo từ db.php)
            $stmt = $pdo->prepare("SELECT * FROM NguoiDung WHERE Email = ? OR SDT = ?");
            $stmt->execute([$login_input, $login_input]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                /* 2. KIỂM TRA MẬT KHẨU 
                   Tôi sẽ kiểm tra cả 2 trường hợp: 
                   - Trường hợp 1: Mật khẩu đã mã hóa (Dùng password_verify)
                   - Trường hợp 2: Mật khẩu thuần (So sánh trực tiếp ===)
                */
                $isPasswordCorrect = false;
                if (password_verify($password, $user['MatKhau'])) {
                    $isPasswordCorrect = true;
                } elseif ($password === $user['MatKhau']) {
                    $isPasswordCorrect = true;
                }

                if ($isPasswordCorrect) {
                    // 3. KIỂM TRA QUYỀN ADMIN
                    if (strtoupper($user['VaiTro']) === 'ADMIN') {
                        // Thiết lập Session
                        $_SESSION['admin_id']   = $user['MaNguoiDung'];
                        $_SESSION['admin_name'] = $user['Ho'] . ' ' . $user['Ten'];
                        $_SESSION['admin_role'] = $user['VaiTro'];
                        
                        // Chuyển hướng thành công
                        header("Location: Admin_Dashboard.php");
                        exit;
                    } else {
                        $error = "Tài khoản này không có quyền quản trị (ADMIN).";
                    }
                } else {
                    $error = "Mật khẩu không chính xác.";
                }
            } else {
                $error = "Tài khoản không tồn tại trong hệ thống.";
            }
        } catch (PDOException $e) {
            $error = "Lỗi kết nối CSDL: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Nhập Quản Trị | LocknLock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .auth-container { max-width: 400px; margin: 100px auto; }
        .auth-box { background: #fff; padding: 30px; border: 1px solid #ddd; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .auth-title { text-align: center; font-size: 1.5rem; font-weight: 700; margin-bottom: 25px; color: #111; }
        .form-control { border-radius: 0; padding: 10px; }
        .form-control:focus { border-color: #000; box-shadow: none; }
        .btn-dark { background-color: #111; border: none; border-radius: 0; padding: 12px; transition: 0.3s; }
        .btn-dark:hover { background-color: #333; }
    </style>
</head>
<body>

<div class="container auth-container">
    <div class="auth-box">
        <div class="auth-title text-uppercase">Admin Login</div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold">Email hoặc Số điện thoại</label>
                <input type="text" name="login_id" class="form-control" placeholder="admin@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-dark w-100 fw-bold">ĐĂNG NHẬP</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="../store_pages/TrangChu.php" class="text-decoration-none small text-muted">Đến cửa hàng</a>
        </div>
    </div>
</div>

</body>
</html>