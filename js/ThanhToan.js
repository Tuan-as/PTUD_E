// FILE: js/ThanhToan.js

const API_ADDR = 'fetchdata/address_action.php';
const API_CHECK = 'fetchdata/process_checkout.php';

let shipFee = 15000;
let discount = 0;
let voucherId = null;

// Khởi chạy khi load trang
document.addEventListener('DOMContentLoaded', () => {
    calcTotal(); 
});

// ======================================= 
// 1. CÁC HÀM TÍNH TOÁN & XỬ LÝ ĐƠN HÀNG 
// ======================================== 

function calcTotal() {
    const addrText = document.getElementById('addr-text') ? document.getElementById('addr-text').value : '';
    
    // Check radio ship
    const shipRadio = document.querySelector('input[name="ship"]:checked');
    const method = shipRadio ? shipRadio.value : 'Nhanh';
    
    if(document.getElementById('ship-name-display')) {
        document.getElementById('ship-name-display').innerText = method === 'HoaToc' ? "Hỏa Tốc" : "Nhanh";
    }

    // Logic phí ship
    if (method === 'HoaToc') {
        shipFee = 50000;
    } else {
        // Freeship nếu đơn > 1tr hoặc ở HCM (dựa vào text địa chỉ)
        if (typeof SUBTOTAL !== 'undefined' && (SUBTOTAL >= 1000000 || /(Hồ Chí Minh|HCM|TP\.HCM)/i.test(addrText))) {
            shipFee = 0;
        } else {
            shipFee = 15000;
        }
    }

    if(document.getElementById('ship-val')) document.getElementById('ship-val').innerText = shipFee.toLocaleString() + '₫';
    
    let currentSub = typeof SUBTOTAL !== 'undefined' ? SUBTOTAL : 0;
    let final = currentSub + shipFee - discount;
    if(final < 0) final = 0;

    if(document.getElementById('temp-total-display')) 
        document.getElementById('temp-total-display').innerText = (currentSub + shipFee).toLocaleString() + '₫';
    
    if(document.getElementById('final-val')) 
        document.getElementById('final-val').innerText = final.toLocaleString() + '₫';
}

function setPay(el, val) {
    document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('pay-method').value = val;
}

function applyVoucher() {
    const sel = document.querySelector('input[name="sel_voucher"]:checked');
    if(sel) {
        const p = sel.closest('.voucher-ticket');
        const min = parseFloat(p.dataset.min);
        
        // Check điều kiện tối thiểu
        if (typeof SUBTOTAL !== 'undefined' && SUBTOTAL < min) {
            alert(`Đơn hàng chưa đạt tối thiểu ${min.toLocaleString()}đ để dùng mã này.`);
            return;
        }

        voucherId = p.dataset.id;
        const type = p.dataset.type;
        const val = parseFloat(p.dataset.val);
        const code = p.dataset.code;

        if(type === 'PERCENT') discount = SUBTOTAL * (val/100);
        else discount = val;

        document.getElementById('voucher-applied-text').innerText = `Đã dùng: ${code}`;
        document.getElementById('disc-val').innerText = '-' + discount.toLocaleString() + '₫';
        calcTotal();
    }
    closeModal('voucherModal');
}

function placeOrder() {
    const addrId = document.getElementById('addr-id').value;
    if (!addrId) { alert("Vui lòng chọn hoặc thêm địa chỉ nhận hàng!"); openAddrModal(); return; }

    const payMethod = document.getElementById('pay-method').value;
    const note = document.getElementById('order-note') ? document.getElementById('order-note').value : '';
    const shipInput = document.querySelector('input[name="ship"]:checked');
    const shipMethod = shipInput ? shipInput.value : 'Nhanh';

    const payload = {
        address_id: addrId, 
        items: typeof ITEMS !== 'undefined' ? ITEMS : [], 
        payment_method: payMethod,
        shipping_method: shipMethod,
        shipping_fee: shipFee, 
        voucher_id: voucherId, 
        order_note: note,
        action: 'place_order'
    };

    const btn = document.querySelector('.btn-place-order');
    const oldText = btn.innerText;
    btn.innerText = "ĐANG XỬ LÝ...";
    btn.disabled = true;

    fetch(API_CHECK, {
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            if (payMethod === 'BANK') {
                window.location.href = `TT_NganHang.php?order_id=${d.order_id}`;
            } else {
                window.location.href = 'TT_ThanhCong.php';
            }
        } else {
            alert("Lỗi: " + d.message);
            btn.innerText = oldText;
            btn.disabled = false;
        }
    })
    .catch(e => {
        console.error(e);
        alert("Có lỗi kết nối server.");
        btn.innerText = oldText;
        btn.disabled = false;
    });
}


// ============================================================
// 2. CÁC HÀM QUẢN LÝ ĐỊA CHỈ (BỔ SUNG CHO HOÀN CHỈNH)
// ============================================================

// --- Modal Utils ---
function openModal(id) { 
    document.getElementById(id).style.display = 'flex'; 
}
function closeModal(id) { 
    document.getElementById(id).style.display = 'none'; 
}

// Hàm mở modal địa chỉ (được gọi từ nút "Thay đổi" trong HTML)
function openAddrModal() {
    openModal('addrModal');
    loadAddrList(); // Load dữ liệu ngay khi mở
}

// --- Load Danh Sách ---
function loadAddrList() {
    const listDiv = document.getElementById('addr-items');
    listDiv.innerHTML = '<p>Đang tải dữ liệu...</p>';

    // Chuyển giao diện sang LIST
    document.getElementById('addr-list-view').style.display = 'block';
    document.getElementById('addr-form-view').style.display = 'none';
    document.getElementById('ft-list').style.display = 'flex';
    document.getElementById('ft-form').style.display = 'none';
    document.getElementById('modal-title').innerText = "Địa Chỉ Của Tôi";

    fetch(API_ADDR, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=get_list'
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            let html = '';
            const currentId = document.getElementById('addr-id').value;

            res.data.forEach(item => {
                // Logic check: Nếu ID trùng ID đang chọn bên ngoài HOẶC (chưa chọn gì mà là mặc định)
                let isChecked = (item.MaDiaChiNhanHang == currentId) ? 'checked' : '';
                if(!currentId && item.LaDiaChiMacDinh === 'Y') isChecked = 'checked';

                const defaultBadge = item.LaDiaChiMacDinh === 'Y' ? 
                    '<span style="color:#d0021b;border:1px solid #d0021b;font-size:10px;padding:0 3px;margin-left:5px;">Mặc định</span>' : '';
                
                html += `
                <div style="border-bottom:1px solid #eee; padding:10px 0; display:flex; gap:10px;">
                    <div style="padding-top:5px;">
                        <input type="radio" name="selected_addr_id" value="${item.MaDiaChiNhanHang}" 
                            data-text="${item.DiaChiNhanHang}" 
                            data-name="${item.TenNhanHang}" 
                            data-phone="${item.SDTNhanHang}" ${isChecked}>
                    </div>
                    <div style="flex:1;">
                        <div><strong>${item.TenNhanHang}</strong> | ${item.SDTNhanHang} ${defaultBadge}</div>
                        <div style="color:#666; font-size:0.9em; margin:5px 0;">${item.DiaChiNhanHang}</div>
                        <div style="font-size:0.85em;">
                            <a href="javascript:void(0)" onclick="showAddrForm('update', ${item.MaDiaChiNhanHang})" style="color:#007bff; margin-right:10px;">Cập nhật</a>
                            ${item.LaDiaChiMacDinh === 'N' ? `<a href="javascript:void(0)" onclick="deleteAddr(${item.MaDiaChiNhanHang})" style="color:red; margin-right:10px;">Xóa</a>` : ''}
                            ${item.LaDiaChiMacDinh === 'N' ? `<button onclick="setAsDefault(${item.MaDiaChiNhanHang})" style="cursor:pointer; border:1px solid #ccc; background:#fff; font-size:10px;">Đặt làm mặc định</button>` : ''}
                        </div>
                    </div>
                </div>`;
            });
            
            if(html === '') html = '<p style="text-align:center; padding:20px;">Bạn chưa có địa chỉ nào.</p>';
            listDiv.innerHTML = html;
        } else {
            listDiv.innerHTML = '<p style="color:red">Lỗi tải danh sách.</p>';
        }
    });
}

// --- Chuyển sang form Thêm/Sửa ---
function showAddrForm(mode, id = null) {
    // UI Switch
    document.getElementById('addr-list-view').style.display = 'none';
    document.getElementById('addr-form-view').style.display = 'block';
    document.getElementById('ft-list').style.display = 'none';
    document.getElementById('ft-form').style.display = 'flex';
    
    // Reset Form
    document.getElementById('frm-addr').reset();
    document.getElementById('form-id').value = '';

    if(mode === 'add') {
        document.getElementById('modal-title').innerText = "Thêm Địa Chỉ Mới";
        document.getElementById('form-action').value = 'add';
    } else {
        document.getElementById('modal-title').innerText = "Cập Nhật Địa Chỉ";
        document.getElementById('form-action').value = 'update';
        document.getElementById('form-id').value = id;
        
        // Gọi API lấy chi tiết để điền vào form
        fetch(API_ADDR, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=get_detail&id=${id}`
        })
        .then(res=>res.json())
        .then(res=>{
            if(res.success){
                const d = res.data;
                document.getElementById('inp-name').value = d.TenNhanHang;
                document.getElementById('inp-phone').value = d.SDTNhanHang;
                
                // Tách chuỗi địa chỉ (Giả định: Cụ thể, Phường, Quận, Tỉnh)
                const parts = d.DiaChiNhanHang.split(', ').reverse(); 
                // parts[0]=Tỉnh, parts[1]=Quận, parts[2]=Phường
                if(parts.length >= 3) {
                    document.getElementById('inp-city').value = parts[0] || '';
                    document.getElementById('inp-district').value = parts[1] || '';
                    document.getElementById('inp-ward').value = parts[2] || '';
                    // Các phần còn lại là địa chỉ cụ thể
                    document.getElementById('inp-specific').value = parts.slice(3).reverse().join(', ');
                } else {
                    document.getElementById('inp-specific').value = d.DiaChiNhanHang;
                }
                
                if(d.LaDiaChiMacDinh === 'Y') document.getElementById('inp-default').checked = true;
            }
        });
    }
}

// --- Quay lại danh sách ---
function showAddrList() {
    loadAddrList();
}

// --- Submit Form (Thêm/Sửa) ---
function submitAddr() {
    const action = document.getElementById('form-action').value;
    const id = document.getElementById('form-id').value;
    const name = document.getElementById('inp-name').value.trim();
    const phone = document.getElementById('inp-phone').value.trim();
    
    const city = document.getElementById('inp-city').value.trim();
    const dist = document.getElementById('inp-district').value.trim();
    const ward = document.getElementById('inp-ward').value.trim();
    const spec = document.getElementById('inp-specific').value.trim();
    
    if(!name || !phone || !city || !dist || !ward || !spec) {
        alert("Vui lòng điền đầy đủ các trường thông tin.");
        return;
    }

    // Ghép chuỗi địa chỉ chuẩn
    const fullAddr = `${spec}, ${ward}, ${dist}, ${city}`;
    const isDefault = document.getElementById('inp-default').checked;

    const body = `action=${action}&id=${id}&name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&addr=${encodeURIComponent(fullAddr)}&is_default=${isDefault}`;

    fetch(API_ADDR, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: body
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            loadAddrList(); // Reload lại list để thấy thay đổi
        } else {
            alert(res.message || "Có lỗi xảy ra.");
        }
    });
}

// --- Xóa địa chỉ ---
function deleteAddr(id) {
    if(!confirm("Bạn chắc chắn muốn xóa địa chỉ này?")) return;
    fetch(API_ADDR, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=delete&id=${id}`
    })
    .then(res=>res.json())
    .then(res=>{
        if(res.success) loadAddrList();
        else alert(res.message);
    });
}

// --- Đặt mặc định ---
function setAsDefault(id) {
    fetch(API_ADDR, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=set_default&id=${id}`
    })
    .then(res=>res.json())
    .then(res=>{
        if(res.success) loadAddrList();
    });
}

// --- Xác nhận chọn địa chỉ (Nút OK ở modal list) ---
function confirmAddrChoice() {
    const selectedRadio = document.querySelector('input[name="selected_addr_id"]:checked');
    if(!selectedRadio) {
        closeModal('addrModal');
        return;
    }

    const id = selectedRadio.value;
    const text = selectedRadio.getAttribute('data-text');
    const name = selectedRadio.getAttribute('data-name');
    const phone = selectedRadio.getAttribute('data-phone');

    // Cập nhật giao diện bên ngoài
    document.getElementById('addr-id').value = id;
    document.getElementById('addr-text').value = text;
    
    // Render lại khung địa chỉ bên ngoài
    document.getElementById('addr-display').innerHTML = `
        <div>
            <span class="addr-info-bold">${name} (+84) ${phone}</span>
            <span class="text-secondary ms-2">${text}</span>
        </div>
        <button class="btn-change-addr" onclick="openAddrModal()">Thay Đổi</button>
        <input type="hidden" id="addr-id" value="${id}">
        <input type="hidden" id="addr-text" value="${text}">
    `;
    
    // Tính lại phí ship (nếu đổi tỉnh thành khác)
    calcTotal();
    closeModal('addrModal');
}