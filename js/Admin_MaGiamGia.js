document.addEventListener("DOMContentLoaded", function () {
      /* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
});
function openPromoForm(id = '') {
    // Sửa đường dẫn: bỏ ../ nếu file JS được nhúng trực tiếp vào Admin_MaGiamGia.php
    fetch(`forms/Admin_MaGiamGia_Form.php?id=${id}`) 
        .then(res => {
            if (!res.ok) throw new Error('Status: ' + res.status);
            return res.text();
        })
        .then(html => {
            document.getElementById('modalTitle').innerText = id ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá';
            document.getElementById('modalBody').innerHTML = html;
            new bootstrap.Modal('#adminModal').show();
        })
        .catch(err => {
            console.error(err);
            alert("Lỗi: " + err.message + ". Không tìm thấy file tại Admin_pages/forms/Admin_MaGiamGia_Form.php");
        });
}

function submitPromoForm(event, form) {
    event.preventDefault();
    const formData = new FormData(form);
    // Tương tự, đường dẫn đến file execute
    fetch('execute/Admin_MaGiamGia_Save.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(err => alert("Lỗi kết nối đến server!"));
}

function togglePromo(id) {
    fetch('execute/Admin_MaGiamGia_Toggle.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    });
}

function deletePromo(id) {
    if (!confirm("Bạn có chắc chắn muốn xóa mã này?")) return;

    // Sử dụng URLSearchParams để gửi dữ liệu dạng Form Data (PHP dễ nhận hơn)
    const data = new URLSearchParams();
    data.append('id', id);

    // Lưu ý: Kiểm tra đường dẫn 'execute/...' có giống 'forms/...' lúc nãy không
    fetch('execute/Admin_MaGiamGia_Delete.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert("Đã xóa mã giảm giá thành công!");
            location.reload(); // Tải lại trang để cập nhật danh sách
        } else {
            alert("Lỗi: " + result.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Không thể kết nối đến server để xóa!");
    });
}