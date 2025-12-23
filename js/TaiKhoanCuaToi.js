// FILE: js/TaiKhoan.js

const API_ACC = 'fetchdata/account_actions.php';
const IMG_PATH = '../img_vid/img_products/';
let currentOrderIdToCancel = null;

document.addEventListener('DOMContentLoaded', () => {
    fetchProfile(); // Hàm này giờ sẽ cập nhật luôn tên hiển thị

    // 1. Logic Sidebar
    document.querySelectorAll('.menu-list > .menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (this.classList.contains('main-account')) {
                const subMenu = this.querySelector('.sub-menu');
                if (subMenu) {
                    const isVisible = subMenu.style.display === 'block' || getComputedStyle(subMenu).display === 'block';
                    if (!this.classList.contains('active') || !isVisible) {
                        document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
                        this.classList.add('active');
                        switchSection('profile'); 
                        document.querySelectorAll('.sub-item').forEach(s => s.classList.remove('active'));
                        this.querySelector('[data-target="profile"]').classList.add('active');
                    }
                }
                return;
            }
            if (this.dataset.target) {
                document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.menu-item.has-sub').forEach(el => el.classList.remove('active'));
                switchSection(this.dataset.target);
                loadSectionData(this.dataset.target);
            }
        });
    });

    document.querySelectorAll('.sub-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.sub-item').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            this.closest('.menu-item').classList.add('active');
            const target = this.dataset.target;
            switchSection(target);
            loadSectionData(target);
        });
    });

    // 2. Tabs
    document.querySelectorAll('.tabs-bar .tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const isReviewTab = this.closest('.tabs-review');
            const container = this.parentElement;
            container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            if(isReviewTab) fetchReviews(this.dataset.type);
            else fetchOrders(this.dataset.status);
        });
    });

    // Forms
    handleFormSubmit('form-profile', 'update_profile');
    handleFormSubmit('form-password', 'change_password');
    document.getElementById('form-address-modal').addEventListener('submit', (e) => {
        e.preventDefault(); saveAddress();
    });

    // Cancel Confirm
    document.getElementById('btn-confirm-cancel').addEventListener('click', confirmCancelOrder);
});

function switchSection(id) {
    document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
    document.getElementById('section-' + id).classList.add('active');
}

function loadSectionData(target) {
    switch(target) {
        case 'address': fetchAddresses(); break;
        case 'orders': fetchOrders('all'); break;
        case 'wishlist': fetchWishlist(); break;
        case 'recent': fetchRecent(); break;
        case 'reviews': fetchReviews('not_reviewed'); break;
    }
}

// --- API ACTIONS ---

async function fetchProfile() {
    const fd = new FormData(); fd.append('action', 'get_profile');
    const res = await fetch(API_ACC, { method: 'POST', body: fd });
    const json = await res.json();
    if(json.success) {
        const d = json.data;
        // Điền dữ liệu vào Form
        document.getElementById('profile-ho').value = d.Ho;
        document.getElementById('profile-ten').value = d.Ten;
        document.getElementById('profile-email').value = d.Email;
        document.getElementById('profile-sdt').value = d.SDT;

        // [MỚI] Cập nhật tên hiển thị ở Sidebar (Chỗ Tài khoản của tôi cũ)
        const sidebarName = document.getElementById('sidebar-username');
        if (sidebarName) {
            // Hiển thị Họ + Tên (Ví dụ: Nguyễn Văn A)
            sidebarName.innerText = `${d.Ho} ${d.Ten}`;
        }
    }
}

async function fetchAddresses() {
    const fd = new FormData(); fd.append('action', 'get_addresses');
    const res = await fetch(API_ACC, { method: 'POST', body: fd });
    const json = await res.json();
    const list = document.getElementById('address-list');
    if(json.success && json.data.length > 0) {
        list.innerHTML = json.data.map(addr => `
            <div class="address-item">
                <div>
                    <strong>${addr.TenNhanHang}</strong> | <span style="color:#777">${addr.SDTNhanHang}</span>
                    ${addr.LaDiaChiMacDinh == 'Y' ? '<span class="badge-default">Mặc định</span>' : ''}
                    <p class="mb-0 mt-1" style="font-size:14px; color:#555">${addr.DiaChiNhanHang}</p>
                </div>
                <div><button class="btn-action" onclick='openAddressModal(${JSON.stringify(addr)})'>Cập nhật</button></div>
            </div>
        `).join('');
    } else { list.innerHTML = '<p class="text-center">Chưa có địa chỉ.</p>'; }
}
async function saveAddress() {
    const form = document.getElementById('form-address-modal');
    const fd = new FormData(form); fd.append('action', 'save_address');
    const res = await fetch(API_ACC, { method: 'POST', body: fd });
    const json = await res.json();
    if(json.success) { alert('Thành công'); closeAddressModal(); fetchAddresses(); } 
    else { alert('Lỗi: ' + json.message); }
}

async function fetchOrders(status) {
    const container = document.getElementById('order-list-container');
    container.innerHTML = '<div class="loading">Đang tải...</div>';
    
    const res = await fetch(`${API_ACC}?action=get_orders&status=${status}`);
    const json = await res.json();

    if(json.success && json.data.length > 0) {
        container.innerHTML = json.data.map(order => {
            const itemsHtml = order.Items.map(item => `
                <div class="order-item">
                    <a href="SP_ChiTiet.php?id=${item.MaSPU}">
                        <img src="${IMG_PATH}${item.HinhAnh || 'no-image.png'}" class="item-img" onerror="this.src='../img_vid/no-image.png'">
                    </a>
                    <div class="item-info">
                        <a href="SP_ChiTiet.php?id=${item.MaSPU}" style="text-decoration:none; color:inherit;">
                            <strong>${item.TenSanPham}</strong>
                        </a>
                        <div style="font-size:13px; color:#777">Phân loại: ${item.TenBienThe}</div>
                        <div>x${item.SoLuong}</div>
                    </div>
                    <div class="item-price text-primary">${parseInt(item.DonGia).toLocaleString('vi-VN')}₫</div>
                </div>
            `).join('');

            let statusText = order.TrangThai;
            let actionButtons = '';

            if(statusText === 'PENDING') {
                statusText = 'CHỜ XÁC NHẬN';
                actionButtons = `<button class="btn-action" onclick="openCancelModal(${order.MaDonHang})">Hủy Đơn</button>`;
            } else if(statusText === 'COMPLETED') {
                statusText = 'HOÀN THÀNH';
                actionButtons = `<button class="btn-action primary" onclick="buyAgain(${order.MaDonHang})">Mua Lại</button>`;
            } else if(statusText === 'CANCELLED') {
                statusText = 'ĐÃ HỦY';
                actionButtons = `<button class="btn-action primary" onclick="buyAgain(${order.MaDonHang})">Mua Lại</button>`;
            } else if(statusText === 'SHIPPING') {
                statusText = 'ĐANG VẬN CHUYỂN';
                actionButtons = `<button class="btn-action" disabled style="color:#aaa; cursor:default">Đang Giao</button>`;
            }

            return `
                <div class="order-card">
                    <div class="order-header">
                        <span>MÃ ĐƠN HÀNG: #${order.MaDonHang}</span>
                        <span class="order-status">${statusText}</span>
                    </div>
                    <div class="order-body">${itemsHtml}</div>
                    <div class="order-footer">
                        <div>Thành tiền: <span class="total-money">${parseInt(order.TongTien).toLocaleString('vi-VN')}₫</span></div>
                        <div class="mt-2">${actionButtons}</div>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        container.innerHTML = '<div style="text-align:center; padding:40px;"><p>Không tìm thấy đơn hàng nào</p></div>';
    }
}

window.openCancelModal = (orderId) => {
    currentOrderIdToCancel = orderId;
    document.getElementById('cancel-modal').style.display = 'block';
}
window.closeCancelModal = () => {
    document.getElementById('cancel-modal').style.display = 'none';
    currentOrderIdToCancel = null;
}
async function confirmCancelOrder() {
    if(!currentOrderIdToCancel) return;
    const reason = document.getElementById('cancel-reason').value;
    
    const fd = new FormData();
    fd.append('action', 'cancel_order');
    fd.append('order_id', currentOrderIdToCancel);
    fd.append('reason', reason);

    const res = await fetch(API_ACC, { method: 'POST', body: fd });
    const json = await res.json();
    
    if(json.success) {
        alert('Đã hủy đơn hàng.');
        closeCancelModal();
        fetchOrders('all'); 
    } else {
        alert('Lỗi: ' + json.message);
    }
}

async function buyAgain(orderId) {
    if(!confirm('Thêm sản phẩm đơn này vào giỏ hàng và thanh toán ngay?')) return;
    const fd = new FormData();
    fd.append('action', 'buy_again');
    fd.append('order_id', orderId);
    const res = await fetch(API_ACC, { method: 'POST', body: fd });
    const json = await res.json();
    if(json.success) window.location.href = 'GioHang.php';
    else alert('Lỗi: ' + json.message);
}

async function fetchWishlist() {
    renderGrid(API_ACC + '?action=get_wishlist', 'wishlist-grid', 'Danh sách trống');
}
async function fetchRecent() {
    renderGrid(API_ACC + '?action=get_recent', 'recent-grid', 'Chưa xem sản phẩm nào');
}
async function renderGrid(url, elementId, emptyMsg) {
    const el = document.getElementById(elementId);
    const res = await fetch(url);
    const json = await res.json();
    if(json.success && json.data.length > 0) {
        el.innerHTML = json.data.map(p => `
            <div class="acc-product-card">
                <a href="SP_ChiTiet.php?id=${p.MaSPU}" style="text-decoration:none; color:inherit">
                    <img src="${IMG_PATH}${p.HinhAnh || 'no-image.png'}" onerror="this.src='../img_vid/no-image.png'">
                    <div class="acc-product-name">${p.TenSanPham}</div>
                    <div style="color:#ee4d2d">${parseInt(p.GiaGoc).toLocaleString('vi-VN')}₫</div>
                </a>
            </div>
        `).join('');
    } else { el.innerHTML = `<p style="grid-column:1/-1; text-align:center">${emptyMsg}</p>`; }
}

async function fetchReviews(type) {
    const container = document.getElementById('review-list-container');
    container.innerHTML = 'Đang tải...';
    const res = await fetch(`${API_ACC}?action=get_reviews_history&type=${type}`);
    const json = await res.json();
    if(json.success && json.data.length > 0) {
        if(type === 'not_reviewed') {
            container.innerHTML = json.data.map(item => `
                <div class="order-card p-3 d-flex align-items-center">
                    <a href="SP_ChiTiet.php?id=${item.MaSPU}">
                        <img src="${IMG_PATH}${item.HinhAnh}" style="width:60px; height:60px; margin-right:15px; border:1px solid #eee">
                    </a>
                    <div style="flex-grow:1">
                        <a href="SP_ChiTiet.php?id=${item.MaSPU}" style="text-decoration:none; color:inherit"><strong>${item.TenSanPham}</strong></a>
                        <div class="small text-muted">Phân loại: ${item.TenBienThe}</div>
                        <div class="small text-muted">Mua ngày: ${item.NgayDat}</div>
                    </div>
                    <a href="SP_ChiTiet.php?id=${item.MaSPU}&review=1" class="btn-primary" style="text-decoration:none">Đánh Giá Ngay</a>
                </div>
            `).join('');
        } else {
             container.innerHTML = json.data.map(item => `
                <div class="order-card p-3">
                    <div class="d-flex mb-2">
                         <a href="SP_ChiTiet.php?id=${item.MaSPU}"><img src="${IMG_PATH}${item.HinhAnh}" style="width:50px; height:50px; margin-right:10px"></a>
                         <div>
                            <a href="SP_ChiTiet.php?id=${item.MaSPU}" style="text-decoration:none; color:inherit"><strong>${item.TenSanPham}</strong></a>
                            <div class="small text-muted">${item.TenBienThe}</div>
                         </div>
                    </div>
                    <div style="background:#f9f9f9; padding:10px; border-radius:4px">
                        <div style="color:#ee4d2d">${'★'.repeat(item.SoDiem)}</div>
                        <div>${item.BaiViet}</div>
                        <div class="small text-muted mt-1">Ngày đánh giá: ${item.NgayDangTai}</div>
                    </div>
                </div>
            `).join('');
        }
    } else { container.innerHTML = '<p class="text-center text-muted mt-4">Không có dữ liệu.</p>'; }
}

function handleFormSubmit(formId, action) {
    const form = document.getElementById(formId); if(!form) return;
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); const fd = new FormData(form); fd.append('action', action);
        try {
            const res = await fetch(API_ACC, { method: 'POST', body: fd });
            const json = await res.json();
            alert(json.message || (json.success ? 'Thành công' : 'Thất bại'));
            if(json.success && action === 'change_password') form.reset();
        } catch(err) { console.error(err); alert('Lỗi kết nối'); }
    });
}
window.openAddressModal = (data = null) => {
    document.getElementById('address-modal').style.display = 'block';
    const form = document.getElementById('form-address-modal'); form.reset();
    if(data) {
        document.getElementById('modal-title').innerText = "Cập nhật địa chỉ";
        document.getElementById('addr-id').value = data.MaDiaChiNhanHang;
        document.getElementById('addr-name').value = data.TenNhanHang;
        document.getElementById('addr-phone').value = data.SDTNhanHang;
        document.getElementById('addr-text').value = data.DiaChiNhanHang;
        document.getElementById('addr-default').checked = (data.LaDiaChiMacDinh === 'Y');
    } else { document.getElementById('modal-title').innerText = "Thêm địa chỉ mới"; document.getElementById('addr-id').value = ""; }
}
window.closeAddressModal = () => { document.getElementById('address-modal').style.display = 'none'; }