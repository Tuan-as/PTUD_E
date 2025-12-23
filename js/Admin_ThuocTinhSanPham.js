document.addEventListener("DOMContentLoaded", function () {
      /* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
});
document.addEventListener('DOMContentLoaded', () => {

  const modal = new bootstrap.Modal(document.getElementById('attrModal'));

  // Thêm
  document.getElementById('btnAddAttr').addEventListener('click', () => {
    document.getElementById('attrModalTitle').innerText = 'Thêm thuộc tính';
    document.getElementById('attrForm').reset();
    document.getElementById('MaThuocTinh').value = '';
    modal.show();
  });

  // Sửa
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const row = this.closest('tr');
      document.getElementById('attrModalTitle').innerText = 'Sửa thuộc tính';
      document.getElementById('TenThuocTinh').value = row.dataset.name;
      modal.show();
    });
  });

  // Submit
  document.getElementById('attrForm').addEventListener('submit', e => {
    e.preventDefault();

    fetch('../execute/Admin_ThuocTinhSanPham_ThuocTinhSave.php', {
      method: 'POST',
      body: new FormData(e.target)
    })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        alert('Lưu thành công');
        location.reload();
      } else {
        alert(res.message);
      }
    })
    .catch(() => alert('Lỗi server'));
  });

});
