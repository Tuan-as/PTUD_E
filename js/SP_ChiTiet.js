// FILE: js/SP_ChiTiet.js

// ĐƯỜNG DẪN TÍNH TỪ FILE PHP (store_pages/)
const API_DETAIL = 'fetchdata/fetch_product_detail.php';
const API_CART = 'fetchdata/cart_action.php';
const API_ACC = 'fetchdata/account_actions.php'; 
const IMG_PATH = '../img_vid/img_products/';
const IMG_REVIEW_PATH = '../img_vid/reviews/';
const NO_IMAGE = '../img_vid/no-image.png';

const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get('id');

let currSku = null;

document.addEventListener('DOMContentLoaded', () => {
    if (productId) {
        fetchProductDetail(productId);
    } else {
        document.querySelector('.main-grid').innerHTML = '<div class="alert alert-danger text-center my-5">Không tìm thấy ID sản phẩm!</div>';
    }

    initZoomEffect();
    setupEventListeners();
});

async function fetchProductDetail(id) {
    try {
        const res = await fetch(`${API_DETAIL}?id=${id}`);
        
        // Kiểm tra nếu API trả về 404 hoặc 500
        if (!res.ok) {
            throw new Error(`Lỗi kết nối Server (${res.status})`);
        }
        
        const data = await res.json();
        
        if (data.error) {
            document.querySelector('.p-title').innerText = data.error;
            return;
        }

        renderProductInfo(data);

    } catch (err) {
        console.error("Fetch Error:", err);
        document.querySelector('.p-title').innerText = "Lỗi tải dữ liệu. Xem Console để biết chi tiết.";
    }
}

function renderProductInfo(data) {
    const info = data.info;
    const variants = data.variants;

    document.title = info.TenSanPham + " | LocknLock";
    
    const breadName = document.getElementById('bread-name');
    if(breadName) breadName.innerText = info.TenSanPham;
    
    const pTitle = document.querySelector('.p-title');
    if(pTitle) pTitle.innerText = info.TenSanPham;
    
    const catTag = document.querySelector('.cat-tag');
    if(catTag) catTag.innerText = info.TenDanhMuc || 'LOCKNLOCK';
    
    const descContent = document.getElementById('desc-content');
    if(descContent) {
        descContent.innerHTML = formatDescription(info.MoTaDai || info.MoTaNgan || '');
    }

    const revCount = document.getElementById('rev-count');
    if(revCount) revCount.innerText = data.review_count;
    renderReviews(data.reviews);

    if (variants.length > 0) {
        renderVariants(variants);
        selectVariant(variants[0]);
    } else {
        const varBox = document.querySelector('.variants-box');
        if(varBox) varBox.innerHTML = '<p class="text-danger small fst-italic">Sản phẩm tạm hết hàng.</p>';
        
        const btnCart = document.querySelector('.btn-cart');
        if(btnCart) {
            btnCart.disabled = true;
            btnCart.innerText = "HẾT HÀNG";
            btnCart.style.background = "#ccc";
            btnCart.style.border = "none";
        }
    }

    // Xử lý nút tim
    const btnWish = document.querySelector('.wishlist-icon');
    if(btnWish) {
        if(data.is_liked) {
            btnWish.classList.add('active');
            const i = btnWish.querySelector('i');
            i.classList.remove('far');
            i.classList.add('fas');
        } else {
            btnWish.classList.remove('active');
            const i = btnWish.querySelector('i');
            i.classList.remove('fas');
            i.classList.add('far');
        }
    }
}

function renderVariants(variants) {
    const container = document.querySelector('.variants-box');
    if(!container) return;
    container.innerHTML = '';
    
    variants.forEach(v => {
        const btn = document.createElement('button');
        btn.className = 'variant-btn';
        let txt = v.ThuocTinh ? v.ThuocTinh : v.Name;
        // Loại bỏ chữ "Màu" thừa nếu có
        txt = txt.replace(/Màu\s+/i, '');
        btn.innerText = txt;
        btn.dataset.sku = v.MaSKU;
        btn.onclick = () => selectVariant(v);
        container.appendChild(btn);
    });
}

function selectVariant(sku) {
    currSku = sku;
    document.querySelectorAll('.variant-btn').forEach(b => {
        b.classList.remove('active');
        if(b.dataset.sku == sku.MaSKU) b.classList.add('active');
    });

    const priceBox = document.querySelector('.p-price');
    if(priceBox) {
        const giaGoc = parseFloat(sku.GiaGoc);
        const giaGiam = sku.GiaGiam ? parseFloat(sku.GiaGiam) : null;
        if (giaGiam && giaGiam < giaGoc) {
            priceBox.innerHTML = `
                <span class="curr-price">${giaGiam.toLocaleString('vi-VN')}₫</span>
                <span class="old-price">${giaGoc.toLocaleString('vi-VN')}₫</span>
                <span class="badge-sale">SALE</span>
            `;
        } else {
            priceBox.innerHTML = `<span class="curr-price">${giaGoc.toLocaleString('vi-VN')}₫</span>`;
        }
    }

    const imgObj = document.getElementById('main-img');
    if (imgObj) {
        if (sku.HinhAnh) imgObj.src = IMG_PATH + sku.HinhAnh;
        else imgObj.src = NO_IMAGE;
        imgObj.onerror = function() { this.src = NO_IMAGE; };
    }

    const thumbList = document.getElementById('thumb-list');
    if(thumbList && sku.Gallery && sku.Gallery.length > 0) {
        thumbList.innerHTML = sku.Gallery.map(src =>
            `<img src="${IMG_PATH + src}" class="thumb-img" onclick="changeMain('${IMG_PATH + src}')"
             style="width:60px; height:60px; object-fit:cover; cursor:pointer; border:1px solid #ddd; margin-right:5px;">`
        ).join('');
    } else {
        if(thumbList) thumbList.innerHTML = '';
    }

    const stockDiv = document.querySelector('.stock-status');
    const btnCart = document.querySelector('.btn-cart');
    
    if (parseInt(sku.TonKho) > 0) {
        if(stockDiv) stockDiv.innerHTML = `<span style="color:#28a745"><i class="fas fa-check-circle"></i> Còn hàng (${sku.TonKho})</span>`;
        if(btnCart) {
            btnCart.disabled = false;
            btnCart.innerText = "THÊM VÀO GIỎ HÀNG";
            btnCart.style.background = "#111";
            btnCart.style.cursor = "pointer";
        }
    } else {
        if(stockDiv) stockDiv.innerHTML = `<span style="color:#dc3545"><i class="fas fa-times-circle"></i> Hết hàng</span>`;
        if(btnCart) {
            btnCart.disabled = true;
            btnCart.innerText = "HẾT HÀNG";
            btnCart.style.background = "#ccc";
            btnCart.style.cursor = "not-allowed";
        }
    }
}

function setupEventListeners() {
    const btnCart = document.querySelector('.btn-cart');
    if (btnCart) {
        btnCart.addEventListener('click', () => {
            if (!currSku) { alert('Vui lòng chọn phân loại sản phẩm!'); return; }
            const qtyInput = document.getElementById('qty');
            const qty = qtyInput ? qtyInput.value : 1;
            
            const oldText = btnCart.innerText;
            btnCart.innerText = "ĐANG XỬ LÝ...";
            btnCart.disabled = true;

            fetch(API_CART, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=add&sku_id=${currSku.MaSKU}&qty=${qty}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if(confirm('Đã thêm vào giỏ hàng! Bạn có muốn đến Giỏ hàng ngay không?')) {
                        window.location.href = 'GioHang.php';
                    }
                } else {
                    if(data.require_login) {
                        if(confirm('Vui lòng đăng nhập để mua hàng. Đến trang đăng nhập?')) window.location.href='DangNhap.php';
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối.');
            })
            .finally(() => {
                btnCart.innerText = oldText;
                btnCart.disabled = false;
            });
        });
    }

    const btnWish = document.querySelector('.wishlist-icon');
    if(btnWish) {
        btnWish.addEventListener('click', function() {
            const formData = new FormData();
            formData.append('action', 'toggle_wishlist');
            formData.append('spu_id', productId);

            fetch(API_ACC, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.require_login) {
                    if(confirm('Vui lòng đăng nhập để lưu yêu thích. Đăng nhập ngay?')) window.location.href='DangNhap.php';
                    return;
                }
                if(data.success) {
                    this.classList.toggle('active');
                    const i = this.querySelector('i');
                    if(this.classList.contains('active')) {
                        i.classList.remove('far'); i.classList.add('fas');
                    } else {
                        i.classList.remove('fas'); i.classList.add('far');
                    }
                } else {
                    alert(data.message || 'Lỗi xử lý');
                }
            })
            .catch(err => console.error(err));
        });
    }

    const formReview = document.getElementById('form-review');
    if (formReview) {
        formReview.addEventListener('submit', async (e) => {
            e.preventDefault();
            const ratingInput = document.querySelector('input[name="rating"]:checked');
            if (!ratingInput) {
                alert('Vui lòng chọn số sao!');
                return;
            }
            const btn = formReview.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = "Đang gửi...";

            const fd = new FormData(formReview);
            fd.append('spu_id', productId); 

            try {
                const res = await fetch(API_DETAIL, { method: 'POST', body: fd });
                const d = await res.json();
                if (d.success) {
                    alert('Cảm ơn đánh giá của bạn!');
                    location.reload();
                } else {
                    alert(d.error || 'Có lỗi xảy ra.');
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi kết nối.');
            } finally {
                btn.disabled = false;
                btn.innerText = "Gửi Đánh Giá";
            }
        });
    }
}

function formatDescription(text) {
    if (!text) return '<p>Đang cập nhật...</p>';
    const keywords = ['Giới thiệu sản phẩm', 'Đặc điểm nổi bật', 'Phù hợp với', 'Lưu ý', 'Thông số kỹ thuật'];
    const lines = text.split('\n');
    let html = '';
    let isListOpen = false;

    lines.forEach(line => {
        line = line.trim();
        if (!line) return;
        let isHeader = false;
        let headerText = '';
        for (const key of keywords) {
            if (line.toLowerCase().includes(key.toLowerCase())) {
                isHeader = true; headerText = line; break;
            }
        }
        if (isHeader) {
            if (isListOpen) { html += '</ul>'; isListOpen = false; }
            html += `<strong class="desc-header">${headerText}</strong>`;
        } else {
            if (!isListOpen) { html += '<ul class="desc-list">'; isListOpen = true; }
            
            // --- ĐÂY LÀ CHỖ TÔI ĐÃ SỬA LỖI ---
            // Thêm dấu ` ` bao quanh chuỗi HTML
            html += `<li>${line}</li>`; 
        }
    });
    if (isListOpen) html += '</ul>';
    return html;
}

window.changeMain = (src) => {
    const img = document.getElementById('main-img');
    if(img) img.src = src;
}

window.updateQty = (delta) => {
    const input = document.getElementById('qty');
    if(!input) return;
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (currSku && val > parseInt(currSku.TonKho)) {
        alert('Vượt quá tồn kho!');
        val = parseInt(currSku.TonKho);
    }
    input.value = val;
}

window.openTab = (name) => {
    document.querySelectorAll('.tab-content').forEach(e => e.style.display='none');
    const target = document.getElementById('tab-'+name);
    if(target) target.style.display='block';
    
    document.querySelectorAll('.tab-link').forEach(b => b.classList.remove('active'));
    const btn = document.querySelector(`.tab-link[onclick="openTab('${name}')"]`);
    if(btn) btn.classList.add('active');
}

function renderReviews(list) {
    const box = document.getElementById('review-list');
    if(!box) return;
    if(!list || list.length === 0) {
        box.innerHTML = '<p style="color:#999; font-style:italic;">Chưa có đánh giá nào.</p>';
        return;
    }
    box.innerHTML = list.map(r => `
        <div class="rev-item">
            <div class="rev-avatar">${r.Ten ? r.Ten.charAt(0).toUpperCase() : 'U'}</div>
            <div class="rev-content">
                <h5>${r.Ho} ${r.Ten} <span class="stars">${'★'.repeat(parseInt(r.SoDiem))}</span></h5>
                <span class="date">${r.NgayDangTai}</span>
                <div class="rev-text">${r.BaiViet || ''}</div>
                ${r.HinhAnhReview ? `<img src="${IMG_REVIEW_PATH}${r.HinhAnhReview}" style="width:80px; height:80px; margin-top:10px; border-radius:4px; object-fit:cover; border:1px solid #eee;">` : ''}
            </div>
        </div>
    `).join('');
}

function initZoomEffect() {
    const container = document.querySelector('.main-img-box');
    const img = document.getElementById('main-img');
    if (!container || !img) return;
    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        img.style.transformOrigin = `${(x/rect.width)*100}% ${(y/rect.height)*100}%`;
        img.style.transform = 'scale(1.6)';
    });
    container.addEventListener('mouseleave', () => {
        img.style.transform = 'scale(1)';
        img.style.transformOrigin = 'center center';
    });
}