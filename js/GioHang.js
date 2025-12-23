// FILE: js/GioHang.js

// Đường dẫn API (Tính từ file PHP gọi script này, ví dụ: store_pages/GioHang.php)
const API_URL = 'fetchdata/cart_action.php';
const IMG_PATH = '../img_vid/img_products/';
let cartData = [];

// Khi trang load xong thì tải giỏ hàng
document.addEventListener('DOMContentLoaded', () => loadCart());

/* =================================================================
   CHỨC NĂNG: TẢI DỮ LIỆU GIỎ HÀNG
   ================================================================= */
function loadCart() {
    const list = document.getElementById('cart-list');
    
    // Tạo hiệu ứng loading
    if(list) list.innerHTML = '<div class="text-center py-5 bg-white">Đang tải giỏ hàng...</div>';

    // Gọi action 'get' từ API PHP
    const url = `${API_URL}?action=get`;

    fetch(url)
    .then(r => r.json())
    .then(d => {
        // Kiểm tra nếu chưa đăng nhập
        if (d.require_login) {
            alert(d.message);
            window.location.href = 'DangNhap.php';
            return;
        }

        if(!d.success) {
            list.innerHTML = `<div class="bg-white p-5 text-center text-danger">${d.message || 'Lỗi tải dữ liệu'}</div>`;
            return;
        }

        cartData = d.data || []; // Dữ liệu trả về nằm trong key 'data'
        renderCart();
    })
    .catch(e => {
        console.error("Lỗi fetch:", e);
        if(list) list.innerHTML = `<div class="bg-white p-5 text-center text-danger">Lỗi kết nối server.</div>`;
    });
}

/* =================================================================
   CHỨC NĂNG: HIỂN THỊ (RENDER) GIỎ HÀNG RA HTML
   ================================================================= */
function renderCart() {
    const list = document.getElementById('cart-list');
    if (!list) return;
   
    // Kiểm tra giỏ trống
    if(cartData.length === 0) {
        list.innerHTML = `
            <div class="bg-white text-center p-5">
                <img src="../img_vid/empty-cart.png" width="100" style="opacity:0.5" onerror="this.style.display='none'">
                <p class="text-muted mt-3">Giỏ hàng của bạn còn trống</p>
                <a href="SanPham.php" class="btn btn-dark mt-2 text-uppercase">Mua ngay</a>
            </div>`;
        updateSummary();
        return;
    }

    // Render danh sách sản phẩm
    list.innerHTML = cartData.map(item => {
        // Chuyển đổi số liệu
        const price = parseFloat(item.GiaHienTai); // Dùng key GiaHienTai từ PHP
        const qty = parseInt(item.SoLuong);
        const total = price * qty;
        
        // Link sản phẩm
        const linkDetail = `SP_ChiTiet.php?id=${item.MaSPU}`;
        
        // Ảnh sản phẩm
        const imgUrl = item.HinhAnh ? `${IMG_PATH}${item.HinhAnh}` : '../img_vid/no-image.png';

        return `
        <div class="cart-item">
            <div class="col-checkbox">
                <input type="checkbox" class="custom-checkbox check-item" 
                       data-price="${price}" 
                       data-qty="${qty}" 
                       data-sku="${item.MaSKU}"
                       onchange="updateSummary()">
            </div>

            <div class="col-product d-flex align-items-center">
                <a href="${linkDetail}" class="me-3">
                    <img src="${imgUrl}" class="item-img" alt="${item.TenSanPham}" onerror="this.src='../img_vid/no-image.png'">
                </a>
                <div>
                    <a href="${linkDetail}" class="item-name fw-bold text-dark text-decoration-none">${item.TenSanPham}</a>
                    <div class="item-variant text-muted small">Phân loại: ${item.TenBienThe}</div>
                </div>
            </div>

            <div class="col-price text-center fw-bold">
                ${price.toLocaleString('vi-VN')}₫
            </div>

            <div class="col-qty text-center">
                <div class="qty-group d-inline-flex border rounded">
                    <button class="btn btn-sm btn-light qty-btn border-0" onclick="changeQty(${item.MaSKU}, -1)">-</button>
                    <input type="text" class="form-control form-control-sm border-0 text-center qty-input" value="${qty}" style="width: 40px;" readonly>
                    <button class="btn btn-sm btn-light qty-btn border-0" onclick="changeQty(${item.MaSKU}, 1)">+</button>
                </div>
            </div>

            <div class="col-total text-center text-danger fw-bold">
                ${total.toLocaleString('vi-VN')}₫
            </div>

            <div class="col-action text-center">
                <button class="btn btn-link text-secondary btn-del" onclick="removeItem(${item.MaSKU})" title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        `;
    }).join('');
   
    updateSummary(); // Cập nhật lại tổng tiền sau khi render
}

/* =================================================================
   CHỨC NĂNG: THAY ĐỔI SỐ LƯỢNG
   ================================================================= */
function changeQty(sku, delta) {
    const item = cartData.find(i => i.MaSKU == sku);
    if (!item) return;

    let newQ = parseInt(item.SoLuong) + delta;
    if (newQ < 1) newQ = 1; // Không cho giảm dưới 1
   
    // Gọi API cập nhật
    const fd = new FormData();
    fd.append('action', 'update'); 
    fd.append('sku_id', sku); 
    fd.append('qty', newQ);
   
    fetch(API_URL, {method:'POST', body:fd})
    .then(r => r.json())
    .then(d => {
        if(d.success) {
            // Cập nhật lại số lượng trong mảng cartData cục bộ để render nhanh
            item.SoLuong = newQ;
            renderCart(); 
        } else {
            alert(d.message); // Hiển thị lỗi (ví dụ: vượt quá tồn kho)
            // Nếu lỗi, load lại toàn bộ giỏ để đồng bộ số lượng cũ
            loadCart();
        }
    })
    .catch(e => console.error(e));
}

/* =================================================================
   CHỨC NĂNG: XÓA SẢN PHẨM KHỎI GIỎ
   ================================================================= */
function removeItem(sku) {
    if(!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
    
    const fd = new FormData();
    fd.append('action', 'remove'); 
    fd.append('sku_id', sku);

    fetch(API_URL, {method:'POST', body:fd})
    .then(r => r.json())
    .then(d => {
        if(d.success) {
            // Xóa khỏi mảng cục bộ và render lại (nhanh hơn gọi loadCart)
            cartData = cartData.filter(i => i.MaSKU != sku);
            renderCart();
        } else {
            alert(d.message);
        }
    })
    .catch(e => console.error(e));
}

/* =================================================================
   CHỨC NĂNG: CHỌN TẤT CẢ (CHECK ALL)
   ================================================================= */
function toggleAll(source) {
    const isChecked = source.checked;
    
    // Đồng bộ checkbox header và footer
    const topCheck = document.getElementById('check-all-top');
    const botCheck = document.getElementById('check-all-bot');
    if(topCheck) topCheck.checked = isChecked;
    if(botCheck) botCheck.checked = isChecked;

    // Tick/Untick tất cả item
    document.querySelectorAll('.check-item').forEach(c => c.checked = isChecked);
    
    updateSummary();
}

/* =================================================================
   CHỨC NĂNG: TÍNH TỔNG TIỀN THANH TOÁN
   ================================================================= */
function updateSummary() {
    let sum = 0;
    let count = 0;
    const allChecks = document.querySelectorAll('.check-item');
    const checkedItems = document.querySelectorAll('.check-item:checked');

    checkedItems.forEach(c => {
        // Lấy giá trị từ data attributes
        const p = parseFloat(c.dataset.price);
        const q = parseInt(c.dataset.qty);
        sum += p * q;
        count++;
    });

    // Hiển thị tổng tiền và số lượng đã chọn
    const totalEl = document.getElementById('cart-total');
    const countEl = document.getElementById('count-selected');
    
    if(totalEl) totalEl.innerText = sum.toLocaleString('vi-VN') + '₫';
    if(countEl) countEl.innerText = count;

    // Logic kiểm tra nút "Chọn tất cả" có nên tick hay không
    const isAllChecked = (allChecks.length > 0 && allChecks.length === checkedItems.length);
    const topCheck = document.getElementById('check-all-top');
    const botCheck = document.getElementById('check-all-bot');
   
    if(topCheck) topCheck.checked = isAllChecked;
    if(botCheck) botCheck.checked = isAllChecked;
}

/* =================================================================
   CHỨC NĂNG: CHUYỂN SANG TRANG THANH TOÁN
   ================================================================= */
function goToCheckout() {
    const checked = document.querySelectorAll('.check-item:checked');
    if(checked.length === 0) { 
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán!'); 
        return; 
    }
   
    // Lấy danh sách SKU ID đã chọn
    const skus = Array.from(checked).map(c => c.dataset.sku);
    
    // Đưa vào input hidden và submit form
    const inputSkus = document.getElementById('input-selected-skus');
    const formCheckout = document.getElementById('form-checkout');
    
    if(inputSkus && formCheckout) {
        inputSkus.value = JSON.stringify(skus); // Hoặc skus.join(',') tùy cách xử lý bên PHP
        formCheckout.submit();
    } else {
        console.error("Không tìm thấy form thanh toán!");
    }
}

/* =================================================================
   CHỨC NĂNG: XÓA CÁC MỤC ĐÃ CHỌN (REMOVE SELECTED)
   ================================================================= */
function removeSelected() {
    const checked = document.querySelectorAll('.check-item:checked');
    if(checked.length === 0) { 
        alert('Vui lòng chọn sản phẩm cần xóa'); 
        return; 
    }
   
    if(!confirm(`Bạn có chắc muốn xóa ${checked.length} sản phẩm đã chọn?`)) return;

    // Lấy danh sách ID cần xóa
    const skusToRemove = Array.from(checked).map(c => c.dataset.sku);
    
    // Gọi API xóa danh sách (tối ưu hơn là gọi nhiều lần API đơn lẻ)
    const fd = new FormData();
    fd.append('action', 'remove_list'); // Cần hỗ trợ action này bên PHP
    fd.append('list_sku', skusToRemove.join(',')); // Gửi chuỗi "1,2,3"

    fetch(API_URL, {method:'POST', body:fd})
    .then(r => r.json())
    .then(d => {
        if(d.success) {
            loadCart(); // Load lại toàn bộ để đảm bảo chính xác
        } else {
            alert(d.message || "Lỗi khi xóa sản phẩm");
        }
    })
    .catch(e => console.error(e));
}