// FILE: js/SanPham.js

// QUAN TRỌNG: Đường dẫn này tính từ file SanPham.php đi vào thư mục fetchdata
const API_URL = 'fetchdata/fetch_products.php'; 

// Đường dẫn ảnh (Đi ra khỏi store_pages (..) -> vào img_vid -> img_products)
const IMG_PATH = '../img_vid/img_products/'; 

const productGrid = document.getElementById('product-grid');
let currentFilter = { category: 'all', min: 0, max: 999999999, sort: 'newest' };

document.addEventListener('DOMContentLoaded', () => {
    // 1. Lấy category từ URL (nếu bấm từ footer)
    const urlParams = new URLSearchParams(window.location.search);
    const catParam = urlParams.get('category');
    if (catParam) currentFilter.category = catParam;

    // 2. Cập nhật UI nút bấm và tải dữ liệu
    updateCategoryButtonsUI(currentFilter.category);
    fetchProducts();

    // 3. Sự kiện click nút danh mục
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentFilter.category = btn.dataset.id;
            updateCategoryButtonsUI(currentFilter.category);
            fetchProducts();
        });
    });

    // 4. Sự kiện lọc giá
    const btnFilter = document.getElementById('btn-apply-filter');
    if(btnFilter) {
        btnFilter.addEventListener('click', () => {
            const minIn = document.getElementById('price-min').value;
            const maxIn = document.getElementById('price-max').value;
            currentFilter.min = minIn ? parseInt(minIn) : 0;
            currentFilter.max = maxIn ? parseInt(maxIn) : 999999999;
            fetchProducts();
        });
    }

    // 5. Sự kiện sắp xếp
    const sortSel = document.getElementById('sort-select');
    if(sortSel) {
        sortSel.addEventListener('change', (e) => {
            currentFilter.sort = e.target.value;
            fetchProducts();
        });
    }
});

function updateCategoryButtonsUI(activeId) {
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.id === activeId) btn.classList.add('active');
    });
}

async function fetchProducts() {
    // Hiển thị loading
    if(productGrid) {
        productGrid.innerHTML = '<div class="loading" style="grid-column:1/-1; text-align:center; padding:20px;">Đang tải dữ liệu từ server...</div>';
        productGrid.style.opacity = '0.5';
    }

    // Tạo URL gọi API
    const url = new URL(API_URL, window.location.href); 
    url.searchParams.append('category', currentFilter.category);
    url.searchParams.append('min', currentFilter.min);
    url.searchParams.append('max', currentFilter.max);
    url.searchParams.append('sort', currentFilter.sort);

    try {
        const response = await fetch(url);
        
        // Kiểm tra lỗi HTTP (404, 500)
        if (!response.ok) {
            throw new Error(`Lỗi kết nối (HTTP ${response.status}). Kiểm tra đường dẫn file PHP.`);
        }

        const data = await response.json();
        
        // Kiểm tra lỗi từ PHP trả về
        if (data.error) throw new Error(data.error);

        renderGrid(data);

    } catch (error) {
        console.error("Lỗi fetch:", error);
        if(productGrid) productGrid.innerHTML = `<p style="color:red; text-align:center; grid-column:1/-1;">Lỗi: ${error.message}</p>`;
    } finally {
        if(productGrid) productGrid.style.opacity = '1';
    }
}

function renderGrid(products) {
    if (!productGrid) return;

    if (!Array.isArray(products) || products.length === 0) {
        productGrid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:50px;">Không tìm thấy sản phẩm nào phù hợp.</div>';
        return;
    }

    const html = products.map(p => {
        const salePrice = parseInt(p.GiaHienThi);
        const originalPrice = parseInt(p.GiaGoc);
        
        let priceHTML = '';
        let badgeHTML = '';
        
        if (salePrice < originalPrice) {
            priceHTML = `
                <div class="price-box">
                    <span class="old-price">${originalPrice.toLocaleString('vi-VN')}₫</span>
                    <span class="sale-price">${salePrice.toLocaleString('vi-VN')}₫</span>
                </div>`;
            badgeHTML = `<span class="badge-sale">SALE</span>`;
        } else {
            priceHTML = `<div class="price-box"><span class="sale-price">${salePrice.toLocaleString('vi-VN')}₫</span></div>`;
        }

        // Xử lý ảnh (quan trọng)
        let imageSource = '../img_vid/no-image.png'; 
        if (p.HinhAnh) imageSource = IMG_PATH + p.HinhAnh;

        const detailLink = `SP_ChiTiet.php?id=${p.MaSPU}`;
        const isStock = parseInt(p.TongTonKho) > 0;
        const stockHtml = isStock 
            ? '<span class="stock in" style="color:#28a745">● Còn hàng</span>' 
            : '<span class="stock out" style="color:#dc3545">● Hết hàng</span>';

        return `
            <a href="${detailLink}" class="product-card">
                <div class="card-img">
                    <img src="${imageSource}" alt="${p.TenSanPham}" onerror="this.src='../img_vid/no-image.png';">
                    ${badgeHTML}
                </div>
                <div class="card-body">
                    <div>
                        <span class="card-cat">${p.TenDanhMuc || 'LocknLock'}</span>
                        <h3 class="card-title">${p.TenSanPham}</h3>
                    </div>
                    <div class="card-bottom">
                        ${priceHTML}
                        ${stockHtml}
                    </div>
                </div>
            </a>
        `;
    }).join('');

    productGrid.innerHTML = html;
}