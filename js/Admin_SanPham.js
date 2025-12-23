document.addEventListener("DOMContentLoaded", function () {

  /* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
});

function openProductForm(id = '') {
  fetch('../admin_pages/forms/Admin_SanPham_SPUForm.php?id=' + id)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalTitle').innerText =
        id ? 'Sửa sản phẩm' : 'Thêm sản phẩm';

      document.getElementById('modalBody').innerHTML = html;
      new bootstrap.Modal('#adminModal').show();
    });
}

function openSkuForm(spuId, skuId = '') {
  fetch(`../admin_pages/forms/Admin_SanPham_SKUForm.php?spu=${spuId}&sku=${skuId}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalTitle').innerText =
        skuId ? 'Sửa biến thể' : 'Thêm biến thể';

      document.getElementById('modalBody').innerHTML = html;
      new bootstrap.Modal('#adminModal').show();
    });
}
function toggleVariants(button, spuId) {
  const productRow = button.closest('tr');
  const nextRow = productRow.nextElementSibling;

  // Nếu đã mở → đóng
  if (nextRow && nextRow.classList.contains('variant-row')) {
    nextRow.remove();
    return;
  }

  // Đóng các variant khác (optional – nhìn gọn)
  document.querySelectorAll('.variant-row').forEach(r => r.remove());

  // Tạo row chứa bảng SKU
  const variantRow = document.createElement('tr');
  variantRow.classList.add('variant-row');

  variantRow.innerHTML = `
    <td colspan="6" class="bg-light">
      <div class="p-3">

        <h6 class="fw-bold mb-3">Danh sách biến thể (SKU)</h6>

        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle mb-3">
            <thead class="table-secondary text-center">
              <tr>
                <th>Mã SKU</th>
                <th>Tên</th>
                <th>Giá gốc</th>
                <th>Giá giảm</th>
                <th>Tồn kho</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="sku-body-${spuId}">
              <tr>
                <td colspan="7" class="text-center text-muted">
                  Đang tải dữ liệu...
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <button class="btn btn-dark btn-sm"
                onclick="openSkuForm(${spuId})">
          + Thêm biến thể
        </button>

      </div>
    </td>
  `;

  productRow.after(variantRow);

  loadSkuBySpu(spuId);
}
function loadSkuBySpu(spuId) {
  fetch('../admin_pages/fetch/Admin_SanPham_GetSKUBySPU.php?spu_id=' + spuId)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById(`sku-body-${spuId}`);

      if (data.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-muted">
              Chưa có biến thể
            </td>
          </tr>
        `;
        return;
      }

      // ĐÂY LÀ ĐOẠN CẦN SỬA CHÍNH XÁC:
      tbody.innerHTML = data.map(sku => `
        <tr class="text-center">
          <td>${sku.SKUCode}</td>
          <td class="text-start">${sku.NameSKU}</td>
          <td>${formatPrice(sku.GiaGoc)}</td>
          <td>${formatPrice(sku.GiaGiam)}</td>
          <td>${sku.TonKho}</td>
          <td>${sku.TrangThai}</td>
          <td>
            <div class="btn-group btn-group-sm">
              <button class="btn btn-outline-dark"
                      onclick="editSku(${sku.MaSKU}, ${spuId})">Sửa</button>
              <button class="btn btn-outline-danger" 
                      onclick="deleteSku(${sku.MaSKU}, ${spuId})">Xoá</button>
            </div>
          </td>
        </tr>
      `).join('');
    });
}

function formatPrice(v) {
  return Number(v).toLocaleString('vi-VN') + ' đ';
}

// Gửi form thêm/sửa biến thể
function submitSkuForm(event, form) {
  event.preventDefault();
  const formData = new FormData(form);

  fetch('../admin_pages/execute/Admin_SanPham_SKUSave.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === 'success'){
      alert('Lưu biến thể thành công!');
      const spuId = formData.get('MaSPU');
      loadSkuBySpu(spuId); // refresh danh sách biến thể
      bootstrap.Modal.getInstance(document.getElementById('adminModal')).hide();
    } else {
      alert('Lỗi: ' + data.message);
    }
  });
}

function deleteSPU(spuId) {
  if (!confirm("Bạn có chắc muốn xoá sản phẩm này?")) return;

  // Dùng URLSearchParams để encode đúng dữ liệu POST
  const formData = new URLSearchParams();
  formData.append('id', spuId);

  fetch('../admin_pages/execute/Admin_SanPham_SPUDelete.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const row = document.querySelector(`button[onclick="deleteSPU(${spuId})"]`).closest('tr');
      row.remove();
      alert("Xoá sản phẩm thành công!");
    } else {
      alert("Lỗi: " + data.message);
    }
  })
  .catch(err => alert("Lỗi server!"));
}

// === Thuộc tính / Giá trị thuộc tính ===
function initThuocTinhForm(skuData = {}) {
  const thuocTinhSelect = document.getElementById('thuocTinhSelect');
  const giaTriSelect = document.getElementById('giaTriSelect');

  if (!thuocTinhSelect || !giaTriSelect) return;

  // Lấy danh sách thuộc tính từ PHP đã render trong select
  // Nếu muốn, bạn có thể fetch từ server nhưng để đơn giản ta dùng sẵn <option> trong form

  function loadGiaTriThuocTinh(maThuocTinh, selectedValue = 0) {
    giaTriSelect.innerHTML = '<option>Đang tải...</option>';

    if (!maThuocTinh) {
      giaTriSelect.innerHTML = '';
      return;
    }

    fetch(`fetch/Admin_SanPham_GetGiaTriThuocTinh.php?thuocTinh=${maThuocTinh}`)
      .then(res => res.json())
      .then(data => {
        giaTriSelect.innerHTML = '';
        data.forEach(gt => {
          const opt = document.createElement('option');
          opt.value = gt.MaGiaTri;
          opt.textContent = gt.TenGiaTri;
          if (gt.MaGiaTri == selectedValue) opt.selected = true;
          giaTriSelect.appendChild(opt);
        });
      });
  }

  // Khi đổi thuộc tính
  thuocTinhSelect.addEventListener('change', function () {
    loadGiaTriThuocTinh(this.value);
  });

  // Nếu là edit, load giá trị ngay
  if (skuData.MaThuocTinh && skuData.MaGiaTri) {
    thuocTinhSelect.value = skuData.MaThuocTinh;
    loadGiaTriThuocTinh(skuData.MaThuocTinh, skuData.MaGiaTri);
  }
}

// Khi mở modal SKUForm
function openSkuForm(spuId, skuId = '') {
  fetch(`../admin_pages/forms/Admin_SanPham_SKUForm.php?spu=${spuId}&sku=${skuId}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalTitle').innerText =
        skuId ? 'Sửa biến thể' : 'Thêm biến thể';

      const modalBody = document.getElementById('modalBody');
      modalBody.innerHTML = html;

      const modal = new bootstrap.Modal('#adminModal');
      modal.show();

      // delay 0ms để đảm bảo DOM đã render xong
      setTimeout(() => {
        // Lấy thông tin edit từ hidden input nếu có
        const thuocTinhSelect = modalBody.querySelector('#thuocTinhSelect');
        const giaTriSelect = modalBody.querySelector('#giaTriSelect');
        let selectedThuocTinh = thuocTinhSelect.value;
        let selectedGiaTri = modalBody.querySelector('input[name="GiaTri"]')?.value || 0;

        if(skuId){
          // Nếu edit, lấy giá trị đã lưu
          const hiddenThuocTinh = modalBody.querySelector('input[name="MaThuocTinh"]');
          const hiddenGiaTri = modalBody.querySelector('input[name="MaGiaTri"]');
          if(hiddenThuocTinh) selectedThuocTinh = hiddenThuocTinh.value;
          if(hiddenGiaTri) selectedGiaTri = hiddenGiaTri.value;
        }

        // Hàm load giá trị thuộc tính
        function loadGiaTriThuocTinh(maThuocTinh, selectedValue = 0){
          giaTriSelect.innerHTML = '<option>Đang tải...</option>';
          if(!maThuocTinh){
            giaTriSelect.innerHTML = '';
            return;
          }
          fetch(`../admin_pages/fetch/Admin_SanPham_GetGiaTriThuocTinh.php?thuocTinh=${maThuocTinh}`)
            .then(res => res.json())
            .then(data => {
              giaTriSelect.innerHTML = '';
              data.forEach(gt => {
                const opt = document.createElement('option');
                opt.value = gt.MaGiaTri;
                opt.textContent = gt.TenGiaTri;
                if(gt.MaGiaTri == selectedValue) opt.selected = true;
                giaTriSelect.appendChild(opt);
              });
            });
        }

        // Load lần đầu
        if(selectedThuocTinh) loadGiaTriThuocTinh(selectedThuocTinh, selectedGiaTri);

        // Khi đổi thuộc tính
        thuocTinhSelect.addEventListener('change', function(){
          loadGiaTriThuocTinh(this.value);
        });

      }, 0); // setTimeout 0 để chắc chắn DOM đã render
    });
}


// === Quản lý Thuộc tính / Giá trị Thuộc tính ===
function openSkuForm(spuId, skuId = '') {
  fetch(`../admin_pages/forms/Admin_SanPham_SKUForm.php?spu=${spuId}&sku=${skuId}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalTitle').innerText = skuId ? 'Sửa biến thể' : 'Thêm biến thể';
      const modalBody = document.getElementById('modalBody');
      modalBody.innerHTML = html;
      
      const modalElt = document.getElementById('adminModal');
      const modal = bootstrap.Modal.getOrCreateInstance(modalElt);
      modal.show();

      // Khởi tạo logic load thuộc tính
      setTimeout(() => initThuocTinhForm(modalBody), 50);
    });
}

function initThuocTinhForm(modalBody) {
    const thuocTinhSelect = modalBody.querySelector('#thuocTinhSelect');
    const giaTriSelect = modalBody.querySelector('#giaTriSelect');
    
    // Lấy giá trị từ các hidden input để phục vụ khi "Sửa"
    const selectedThuocTinh = modalBody.querySelector('#selectedThuocTinh')?.value || "";
    const selectedGiaTri = modalBody.querySelector('#selectedGiaTri')?.value || "";

    function loadGiaTri(maThuocTinh, valToSelect = 0) {
        if (!maThuocTinh) {
            giaTriSelect.innerHTML = '<option value="">Chọn giá trị</option>';
            return;
        }
        
        giaTriSelect.innerHTML = '<option>Đang tải...</option>';

        // Đảm bảo đường dẫn này khớp với cấu trúc thư mục của bạn
        fetch(`fetch/Admin_SanPham_GetGiaTriThuocTinh.php?thuocTinh=${maThuocTinh}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                giaTriSelect.innerHTML = '<option value="">Chọn giá trị</option>';
                data.forEach(gt => {
                    const opt = document.createElement('option');
                    opt.value = gt.MaGiaTri;
                    opt.textContent = gt.TenGiaTri;
                    if (gt.MaGiaTri == valToSelect) opt.selected = true;
                    giaTriSelect.appendChild(opt);
                });
            })
            .catch(err => {
                console.error("Fetch error:", err);
                giaTriSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            });
    }

    // Sự kiện khi người dùng thay đổi thuộc tính trên form
    thuocTinhSelect.addEventListener('change', function() {
        loadGiaTri(this.value);
    });

    // Nếu đang mở form "Sửa" (đã có sẵn thuộc tính), thực hiện load ngay lập tức
    if (selectedThuocTinh && selectedThuocTinh != "0") {
        loadGiaTri(selectedThuocTinh, selectedGiaTri);
    }
}

function editSku(skuId, spuId) {
  openSkuForm(spuId, skuId);
}

function deleteSku(skuId, spuId) {
  if (!confirm("Bạn có chắc muốn xoá biến thể này?")) return;
  
  const formData = new URLSearchParams();
  formData.append('id', skuId);

  fetch('../admin_pages/execute/Admin_SanPham_SKUDelete.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert("Xoá thành công!");
      loadSkuBySpu(spuId); // Tự động load lại bảng biến thể
    } else {
      alert("Lỗi: " + data.message);
    }
  });
}
