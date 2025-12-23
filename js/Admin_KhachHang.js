document.addEventListener("DOMContentLoaded", function () {
      /* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
});

/**
 * Hàm hiển thị chi tiết khách hàng
 * @param {Object} data - Dữ liệu khách hàng từ PHP truyền sang
 */
function showCustomerDetail(data) {
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    
    if (!modalTitle || !modalBody) return;

    modalTitle.innerText = "Thông tin chi tiết: " + data.name;
    const totalSpentFormatted = Number(data.total_spent).toLocaleString('vi-VN') + ' đ';
    
    // Tạo link điều hướng: encodeURIComponent giúp xử lý các tên có dấu hoặc ký tự đặc biệt
    const historyUrl = `Admin_DonHang.php?search_customer=${encodeURIComponent(data.name)}`;
    
    modalBody.innerHTML = `
        <div class="p-2">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Thông tin cá nhân</h6>
                    <p class="mb-2"><strong>Mã khách hàng:</strong> <span class="text-primary">#KH${data.id}</span></p>
                    <p class="mb-2"><strong>Họ tên:</strong> ${data.name}</p>
                    <p class="mb-2"><strong>Email:</strong> ${data.Email}</p>
                    <p class="mb-2"><strong>Số điện thoại:</strong> ${data.phone || '<i>Chưa cập nhật</i>'}</p>
                    <p class="mb-0"><strong>Ngày tham gia:</strong> ${data.register_date}</p>
                </div>
                <div class="col-lg-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Hoạt động mua hàng</h6>
                    <p class="mb-2"><strong>Tổng số đơn hàng:</strong> <span class="badge bg-dark">${data.total_orders} đơn</span></p>
                    <p class="mb-2"><strong>Tổng chi tiêu:</strong> <span class="text-danger fw-bold">${totalSpentFormatted}</span></p>
                    <p class="mb-0"><strong>Mức độ:</strong> ${getBuyLevelBadge(data.total_orders)}</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small italic">Dữ liệu hệ thống Lock&Lock</span>
                <button class="btn btn-dark btn-sm rounded-0" onclick="window.location.href='${historyUrl}'">
                    XEM LỊCH SỬ ĐƠN HÀNG
                </button>
            </div>
        </div>
    `;

    const detailModal = new bootstrap.Modal(document.getElementById('adminModal'));
    detailModal.show();
}

function getBuyLevelBadge(count) {
    if (count > 10) return '<span class="badge bg-success">Mua nhiều</span>';
    if (count > 3) return '<span class="badge bg-primary">Mua vừa</span>';
    return '<span class="badge bg-secondary">Mua ít</span>';
}

/**
 * Hàm phụ để hiển thị nhãn mức độ mua hàng trong modal
 */
function getBuyLevelBadge(count) {
    if (count > 10) return '<span class="badge bg-success">Mua nhiều</span>';
    if (count > 3) return '<span class="badge bg-primary">Mua vừa</span>';
    return '<span class="badge bg-secondary">Mua ít</span>';
}