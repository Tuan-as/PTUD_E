<?php
session_start();
// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../db.php';

// 1. CHẶN TRUY CẬP: Nếu chưa đăng nhập thì không được vào trang này
if (!isset($_SESSION['user_id'])) {
    header("Location: DangNhap.php");
    exit;
}

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Validation cơ bản
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $error = "Vui lòng điền đầy đủ các trường.";
    } elseif (strlen($new_pass) < 6) {
        $error = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        // 2. KIỂM TRA MẬT KHẨU CŨ TRONG DB
        $sql = "SELECT MatKhau FROM NguoiDung WHERE MaNguoiDung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($current_pass, $user['MatKhau'])) {
            // Mật khẩu cũ đúng -> Tiến hành cập nhật
            
            // 3. CẬP NHẬT MẬT KHẨU MỚI
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            
            $updateSql = "UPDATE NguoiDung SET MatKhau = ? WHERE MaNguoiDung = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("si", $new_hash, $user_id);
            
            if ($stmtUpdate->execute()) {
                $message = "Đổi mật khẩu thành công!";
            } else {
                $error = "Lỗi hệ thống, vui lòng thử lại.";
            }
        } else {
            $error = "Mật khẩu hiện tại không đúng.";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đổi Mật Khẩu | LocknLock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/DangNhap.css"> 
</head>
<body class="bg-light">

<div class="container mt-3">
    <a href="TrangChu.php" class="text-decoration-none text-dark fw-bold">
        <i class="fas fa-arrow-left"></i> Quay lại Trang chủ
    </a>
</div>

<div class="container min-vh-100 d-flex align-items-center justify-content-center" style="margin-top: -50px;">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="login-box shadow-sm">
                <h3 class="login-title text-center mb-4">ĐỔI MẬT KHẨU</h3>

                <?php if ($message): ?>
                    <div class="alert alert-success text-center">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger text-center">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Nhập lại mật khẩu mới</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-dark w-100 py-2">XÁC NHẬN ĐỔI</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>