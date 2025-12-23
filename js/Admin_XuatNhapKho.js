document.addEventListener("DOMContentLoaded", function () {
    // Hiệu ứng hiện trang
    document.querySelectorAll('.fade-up').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });
});

// 1. Mở modal xem chi tiết
function openInventoryDetail(id) {
    const modalBody = document.getElementById('inventoryModalBody');
    document.getElementById('inventoryModalTitle').innerText = "CHI TIẾT PHIẾU KHO #" + id;
    
    modalBody.innerHTML = '<div class="text-center p-5 small text-muted text-uppercase">Đang tải dữ liệu...</div>';
    const myModal = new bootstrap.Modal(document.getElementById('inventoryModal'));
    myModal.show();

    fetch('fetch/Admin_XuatNhapKho_GetDetail.php?id=' + id)
        .then(res => res.text())
        .then(html => { modalBody.innerHTML = html; })
        .catch(err => { modalBody.innerHTML = '<div class="alert alert-danger">Lỗi tải dữ liệu!</div>'; });
}

// 2. Mở modal thêm phiếu mới
function openInventoryForm() {
    const modalBody = document.getElementById('inventoryModalBody');
    document.getElementById('inventoryModalTitle').innerText = "TẠO PHIẾU XUẤT NHẬP KHO";
   
    fetch('forms/Admin_XuatNhapKho_Form.php')
        .then(res => res.text())
        .then(html => {
            modalBody.innerHTML = html;
            const inventoryModalElement = document.getElementById('inventoryModal');
            const myModal = new bootstrap.Modal(inventoryModalElement);
            myModal.show();

            // QUAN TRỌNG: Gắn sự kiện submit sau khi form đã được đưa vào DOM
            const form = document.getElementById('formCreateInventory');
            if (form) {
                form.addEventListener('submit', function (e) {
                    submitInventoryForm(e);
                });
            }
        });
}

let isProcessing = false;

function submitInventoryForm(event) {
    if (event) event.preventDefault(); // Chặn load lại trang
   
    if (isProcessing) return;

    const form = document.getElementById('formCreateInventory');
    const submitBtn = document.getElementById('btnSubmitInventory');
   
    if (!form || !submitBtn) return;

    isProcessing = true;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

    const formData = new FormData(form);
   
    fetch('execute/Admin_XuatNhapKho_Save.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
            // Reset lại trạng thái để người dùng bấm lại được
            isProcessing = false;
            submitBtn.disabled = false;
            submitBtn.innerText = 'XÁC NHẬN LƯU PHIẾU';
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        isProcessing = false;
        submitBtn.disabled = false;
        submitBtn.innerText = 'XÁC NHẬN LƯU PHIẾU';
    });
}