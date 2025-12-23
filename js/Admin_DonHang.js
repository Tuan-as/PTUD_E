document.addEventListener("DOMContentLoaded", function () {
    // Hiệu ứng Fade-up đồng bộ với trang Sản phẩm
    document.querySelectorAll('.fade-up').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });
});
/**
 * Mở modal chi tiết đơn hàng
 */
function openOrderDetail(orderId) {
    // 1. Lấy phần thân của Modal để chèn dữ liệu
    const modalBody = document.getElementById('orderModalBody');
    if (!modalBody) return;

    modalBody.innerHTML = '<div class="text-center p-5 small text-muted text-uppercase">Đang tải dữ liệu...</div>';
    
    // 2. Kích hoạt hiển thị Modal trước
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
    orderModal.show();

    // 3. Gọi AJAX lấy dữ liệu từ file fetch
    // Lưu ý: Đường dẫn từ Admin_DonHang.php vào thư mục fetch là 'fetch/...'
    fetch('fetch/Admin_DonHang_GetDetail.php?id=' + orderId)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(err => {
            console.error('Lỗi:', err);
            modalBody.innerHTML = '<div class="alert alert-dark rounded-0 small">Không thể tải dữ liệu đơn hàng. Vui lòng thử lại!</div>';
        });
}

/**
 * Cập nhật trạng thái đơn hàng
 */
function updateOrderStatus(orderId, status) {
    if (!confirm('Xác nhận thay đổi trạng thái đơn hàng này?')) return;

    const formData = new FormData();
    formData.append('id', orderId);
    formData.append('status', status);

    // Đường dẫn vào thư mục execute
    fetch('execute/Admin_DonHang_UpdateStatus.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(err => alert('Lỗi kết nối hệ thống!'));
}