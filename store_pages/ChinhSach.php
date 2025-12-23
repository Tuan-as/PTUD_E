<?php
// FILE: store_pages/ChinhSach.php

// 1. BẬT BÁO LỖI (Để xem lỗi gì thay vì màn hình trắng 500)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. KẾT NỐI DATABASE CHUNG (Để dùng cho Header/Footer nếu cần)
// Kiểm tra file db.php có tồn tại không
if (file_exists('../db.php')) {
    require_once '../db.php';
} else {
    die("Lỗi: Không tìm thấy file ../db.php");
}

// 3. KẾT NỐI DATABASE RIÊNG CỦA CHÍNH SÁCH
// Thông tin kết nối (Dựa trên XAMPP mặc định)
$sv_name = "localhost";
$sv_user = "root";
$sv_pass = ""; // Mặc định XAMPP không có pass
$sv_db   = "ChinhSach.db"; // Tên database theo ảnh bạn gửi

$conn_policy = new mysqli($sv_name, $sv_user, $sv_pass, $sv_db);

// Kiểm tra kết nối riêng
if ($conn_policy->connect_error) {
    die("Lỗi kết nối database ChinhSach.db: " . $conn_policy->connect_error);
}
$conn_policy->set_charset("utf8mb4");

// 4. XỬ LÝ DỮ LIỆU
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'privacy';
$page_title_display = "Chính Sách";
$menu_items = [];
$sections = [];
$error_msg = "";

// A. Lấy Menu
$sql_menu = "SELECT slug, title FROM pages ORDER BY id ASC";
$res_menu = $conn_policy->query($sql_menu);

if ($res_menu) {
    while ($row = $res_menu->fetch_assoc()) {
        $menu_items[] = $row;
    }
} else {
    // Nếu lỗi truy vấn menu -> báo lỗi SQL
    die("Lỗi truy vấn Menu: " . $conn_policy->error);
}

// B. Lấy Trang hiện tại
$stmt_page = $conn_policy->prepare("SELECT id, title FROM pages WHERE slug = ?");
if ($stmt_page) {
    $stmt_page->bind_param("s", $slug);
    $stmt_page->execute();
    $res_page = $stmt_page->get_result();

    if ($res_page->num_rows > 0) {
        $page_data = $res_page->fetch_assoc();
        $page_id = $page_data['id'];
        $page_title_display = $page_data['title'];

        // C. Lấy Nội dung (Sections)
        $stmt_sec = $conn_policy->prepare("SELECT title, content FROM sections WHERE page_id = ? ORDER BY sort_order ASC");
        if ($stmt_sec) {
            $stmt_sec->bind_param("i", $page_id);
            $stmt_sec->execute();
            $res_sec = $stmt_sec->get_result();
            while ($row = $res_sec->fetch_assoc()) {
                $sections[] = $row;
            }
            $stmt_sec->close();
        } else {
            die("Lỗi chuẩn bị truy vấn Sections: " . $conn_policy->error);
        }
    } else {
        $error_msg = "Không tìm thấy trang có đường dẫn: " . htmlspecialchars($slug);
    }
    $stmt_page->close();
} else {
    die("Lỗi chuẩn bị truy vấn Pages: " . $conn_policy->error);
}

$conn_policy->close();

// 5. HIỂN THỊ GIAO DIỆN
$page_title = $page_title_display . " | LocknLock";
$page_css = "../css/ChinhSach.css"; 

// Kiểm tra đường dẫn include
if (file_exists('includes/header.php')) {
    include 'includes/header.php';
} else {
    echo "<h1>Lỗi: Không tìm thấy ../includes/header.php</h1>";
}
?>

<main class="policy-main-content">
    <div class="policy-container">
        
        <h1><?php echo htmlspecialchars($page_title_display); ?></h1>

        <nav class="policy-menu">
            <?php if (!empty($menu_items)): ?>
                <?php foreach ($menu_items as $item): ?>
                    <a href="ChinhSach.php?slug=<?php echo htmlspecialchars($item['slug']); ?>" 
                       class="<?php echo ($item['slug'] == $slug) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có menu nào.</p>
            <?php endif; ?>
        </nav>
        
        <?php if ($error_msg): ?>
            <div style="text-align: center; padding: 50px; color: red;">
                <h3><?php echo $error_msg; ?></h3>
                <a href="TrangChu.php" style="color: #333;">Về Trang Chủ</a>
            </div>
        <?php elseif (empty($sections)): ?>
            <div style="text-align: center; padding: 50px;">
                <p>Nội dung đang được cập nhật.</p>
            </div>
        <?php else: ?>
            <div class="policy-content">
                <?php foreach ($sections as $section): ?>
                    <div class="accordion-item">
                        <button class="accordion-button">
                            <?php echo htmlspecialchars($section['title']); ?>
                        </button>
                        <div class="accordion-panel">
                            <div class="panel-inner">
                                <?php echo $section['content']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div> 
</main>

<script src="../js/ChinhSach.js"></script>

<?php 
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?>