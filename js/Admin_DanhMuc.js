document.addEventListener('DOMContentLoaded', () => {
/* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
  // Nút Thêm mới
  document.querySelector('button.btn-dark.btn-sm').addEventListener('click', () => {
    document.getElementById('categoryModalTitle').innerText = 'Thêm danh mục';
    document.getElementById('MaDanhMuc').value = '';
    document.getElementById('TenDanhMuc').value = '';
    document.getElementById('MoTa').value = '';
    new bootstrap.Modal(document.getElementById('categoryModal')).show();
  });

  // Nút Sửa
  document.querySelectorAll('button.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const row = this.closest('tr');
      const id = row.dataset.id;
      const name = row.querySelector('td:nth-child(1)').innerText.trim();
      const desc = row.querySelector('td:nth-child(3)').innerText.trim();

      document.getElementById('categoryModalTitle').innerText = 'Sửa danh mục';
      document.getElementById('MaDanhMuc').value = id;
      document.getElementById('TenDanhMuc').value = name;
      document.getElementById('MoTa').value = desc;

      new bootstrap.Modal(document.getElementById('categoryModal')).show();
    });
  });

  // Nút Xem -> chuyển sang Admin_SanPham.php với danh mục đã chọn
  document.querySelectorAll('button.btn-view').forEach(btn => {
    btn.addEventListener('click', function() {
      const row = this.closest('tr');
      const categoryId = row.dataset.id;
      // Chuyển trang với query string search_category
      window.location.href = `Admin_SanPham.php?search_category=${categoryId}`;
    });
  });

  // Submit form thêm/sửa
  document.getElementById('categoryForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../admin_pages/execute/Admin_DanhMuc_DanhMucSave.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if(data.success){
        alert('Lưu danh mục thành công!');
        location.reload();
      } else {
        alert('Lỗi: ' + data.message);
      }
    })
    .catch(err => alert('Lỗi server: '+err));
  });

});
