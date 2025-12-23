<?php
// --- CẤU HÌNH TRANG ---
$page_title = "Giải thưởng | LocknLock";
$page_css = "../css/GT_GiaiThuong.css"; 

// --- KẾT NỐI DB ---
require '../db.php'; // Đã sửa đường dẫn

include 'includes/header.php'; 

/* ============================
  1. Lấy danh sách giải thưởng
============================ */
$award_groups = [];
$res = $conn->query("SELECT * FROM awards ORDER BY id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $countRes = $conn->query("SELECT COUNT(*) AS total FROM award_images WHERE award_id = " . $row['id']);
        $row['count'] = ($countRes) ? $countRes->fetch_assoc()['total'] : 0;
        $award_groups[] = $row;
    }
}

/* ==============================
  2. Lấy toàn bộ ảnh
============================== */
$award_images = [];
$resImages = $conn->query("SELECT * FROM award_images");
if ($resImages) {
    while ($row = $resImages->fetch_assoc()) {
        $award_images[$row['award_id']][] = $row;
    }
}

/* ===========================
  3. Lấy highlight
=========================== */
$highlights = [];
$resHL = $conn->query("SELECT * FROM highlights ORDER BY id ASC");
if ($resHL) {
    while ($row = $resHL->fetch_assoc()) {
        $highlights[] = $row;
    }
}
?>

<main class="award-page">

   <section class="intro section-container section-margin-top">
       <h1 style="text-align: center; font-size: 2.5em; font-weight: bold; margin-bottom: 30px;">Giải thưởng LocknLock</h1>
   </section>

   <section class="award-links section-container">
       <?php if (!empty($award_groups)): ?>
           <?php foreach($award_groups as $g): ?>
               <div class="award-card">
                   <a href="#award-<?= $g['id'] ?>"><?= htmlspecialchars($g['title']) ?></a>
                   <span>(<?= $g['count'] ?>)</span>
               </div>
           <?php endforeach; ?>
       <?php else: ?>
           <p style="text-align:center">Chưa có dữ liệu giải thưởng.</p>
       <?php endif; ?>
   </section>

   <?php foreach($award_groups as $g): ?>
       <section id="award-<?= $g['id'] ?>" class="award-section hidden section-container section-margin-top">
           <div class="award-header">
               <h2><?= htmlspecialchars($g['title']) ?></h2>
               <?php if(!empty($g['description'])): ?>
                   <p><?= htmlspecialchars($g['description']) ?></p>
               <?php endif; ?>
           </div>

           <div class="award-images">
               <?php if(!empty($award_images[$g['id']])): ?>
                   <?php foreach($award_images[$g['id']] as $img): ?>
                       
                       <?php 
                           // --- XỬ LÝ SỬA LỖI ĐƯỜNG DẪN ẢNH ---
                           // Lấy tên file gốc (ví dụ: GT_all-tritan.png) từ đường dẫn dài
                           $filename = basename($img['image_path']); 
                           // Tạo đường dẫn mới trỏ thẳng vào img_vid
                           $real_path = "../img_vid/" . $filename;
                       ?>

                       <div class="img-item">
                           <img src="<?= htmlspecialchars($real_path) ?>" 
                                alt="<?= htmlspecialchars($img['alt_text'] ?? 'Award Image') ?>">
                           <?php if(!empty($img['product_name'])): ?>
                               <p class="img-caption"><?= htmlspecialchars($img['product_name']) ?></p>
                           <?php endif; ?>
                       </div>

                   <?php endforeach; ?>
               <?php else: ?>
                   <p>Đang cập nhật hình ảnh.</p>
               <?php endif; ?>
           </div>
          
            <?php if(!empty($award_images[$g['id']]) && count($award_images[$g['id']]) > 8): ?>
            <div class="pagination-bar" data-award="<?= $g['id'] ?>">
               <button class="page-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
               <div class="page-numbers"></div>
               <button class="page-btn next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
            <?php endif; ?>

       </section>
   <?php endforeach; ?>

    <div class="highlights-wrapper section-margin-top">
        <?php foreach($highlights as $hl): ?>
           
           <?php 
               // --- XỬ LÝ ẢNH HIGHLIGHT ---
               $hl_filename = basename($hl['image_main']);
               $hl_path = "../img_vid/" . $hl_filename;

               $popup_filename = basename($hl['image_popup']);
               $popup_path = "../img_vid/" . $popup_filename;
           ?>

           <section class="highlight hidden">
               <div class="highlight-img-wrapper">
                   <img src="<?= htmlspecialchars($hl_path) ?>" class="highlight-img">
               </div>

               <div class="highlight-text">
                   <h3><?= htmlspecialchars($hl['title']) ?></h3>
                   <p><?= htmlspecialchars($hl['description_short']) ?></p>

                   <button class="xem-them" data-popup="popup-<?= $hl['id'] ?>">
                       Xem thêm
                   </button>
               </div>
           </section>

           <div class="popup" id="popup-<?= $hl['id'] ?>">
               <div class="popup-content">
                   <span class="close">&times;</span>
                   
                   <div class="popup-body">
                       <div class="popup-left">
                            <img src="<?= htmlspecialchars($popup_path) ?>">
                       </div>
                       <div class="popup-right">
                           <h3><?= htmlspecialchars($hl['popup_title']) ?></h3>
                           <p><?= nl2br(htmlspecialchars($hl['popup_text'])) ?></p>
                       </div>
                   </div>
               </div>
           </div>
        <?php endforeach; ?>
    </div>

</main>

<script src="../js/GT_GiaiThuong.js"></script>

<?php 
include 'includes/footer.php'; 
?>