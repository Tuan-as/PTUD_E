<?php 
    // Định nghĩa các biến cho header
    $page_title = "Thông tin doanh nghiệp | LocknLock";
    $page_css = "../css/GT_ThongTinDoanhNghiep.css"; 

    // Gắn Header  
    include 'includes/header.php'; 
?>

<main class="thongtin-page">
    <h1 class="thongtin-title">Thông tin doanh nghiệp</h1>

    <section class="thongtin-list-section">
      <div class="thongtin-list-container">
        <table class="thongtin-table">
          <thead>
            <tr>
              <th>Phân loại</th>
              <th>Tiêu đề</th>
              <th>Ngày đăng</th>
            </tr>
          </thead>

          <tbody id="trang1" class="trang-hien">
            <tr class="thongtin-item"><td>Thông báo</td><td>Công bố kết quả kinh doanh năm tài chính 2024</td><td>28/03/2025</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Báo cáo hợp nhất giữa LocknLock và chi nhánh Việt Nam</td><td>03/02/2025</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Thay đổi Giám đốc điều hành khu vực Đông Nam Á</td><td>30/12/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Phát hành cổ phiếu mới</td><td>21/10/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Báo cáo thường niên và kiểm toán năm 2024</td><td>04/10/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Thư mời Đại hội cổ đông thường niên 2024</td><td>04/10/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Xác nhận danh sách cổ đông có quyền biểu quyết</td><td>30/08/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Thông báo kết toán kỳ tài chính lần thứ 19 của LocknLock Co., Ltd.</td><td>29/03/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Hướng dẫn ủy quyền tham dự Đại hội đồng cổ đông thường niên lần thứ 19 (2024.03.29)</td><td>14/03/2024</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Hướng dẫn ủy quyền tham dự Đại hội đồng cổ đông bất thường (2023.12.22)</td><td>07/12/2023</td></tr>
          </tbody>

          <tbody id="trang2" class="trang-an">
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Ngày xác nhận cổ đông cho Đại hội cổ đông bất thường</td><td>14/11/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Điều chỉnh công bố về ngày hủy cổ phiếu và phản hồi của chủ nợ</td><td>19/09/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Thông báo ngày hủy cổ phiếu và phản hồi của chủ nợ</td><td>18/09/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Ngày xác nhận cổ đông cho Đại hội cổ đông bất thường</td><td>07/08/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Ngày xác nhận cổ đông cho Đại hội cổ đông bất thường</td><td>30/05/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Báo cáo tài chính năm thứ 18 của LocknLock</td><td>31/03/2023</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Kết quả Đại hội cổ đông bất thường lần thứ 18</td><td>17/10/2022</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Mời họp Đại hội cổ đông bất thường lần thứ 18</td><td>30/09/2022</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>[Thông báo] Xác định danh sách cổ đông có quyền biểu quyết</td><td>08/09/2022</td></tr>
            <tr class="thongtin-item"><td>Thông báo</td><td>Báo cáo tài chính năm thứ 17 của LocknLock</td><td>31/03/2022</td></tr>
          </tbody>
        </table>
      </div>

      <div class="pagination-bar">
        <button id="prevBtn" class="page-btn"><</button>
        <div id="pageNumbers"></div>
        <button id="nextBtn" class="page-btn">></button>
      </div>

    </section>
</main>

<script src="../js/GT_ThongTinDoanhNghiep.js"></script>

<?php 
    // Gắn Footer 
    include 'includes/footer.php'; 
?>