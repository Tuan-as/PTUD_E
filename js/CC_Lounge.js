// file: CC_Lounge.js - LOGIC XỬ LÝ TRANG LOUNGE

// Hằng số cho phân trang
const ARTICLES_PER_PAGE = 4;  
const API_URL = '../php/fetch_lounge.php';  

// Lấy các phần tử DOM
const articlesGrid = document.getElementById('articles-grid');
const filterBtns = document.querySelectorAll('.filter-btn');
const paginationBar = document.getElementById('pagination-bar');

// Biến trạng thái
let allArticlesData = [];
let currentCategory = 'all'; // Đây là key Tiếng Anh (giống data-category)
let currentPage = 1;

// BẢN ĐỒ CHUYỂN ĐỔI:
// Dùng để dịch từ data-category="tips" (Tiếng Anh)
// sang "Mẹo sử dụng" (Tiếng Việt) mà file PHP trả về.
const CATEGORY_MAP = {
    'recipe': 'Công thức nấu ăn',
    'tips': 'Mẹo sử dụng',
    'eco': 'Sống xanh',
    'all': 'Tất cả' 
};

// --- CHỨC NĂNG: TẢI DỮ LIỆU TỪ API ---
async function fetchArticlesData() {
    try {
        const response = await fetch(API_URL);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (Array.isArray(data) && data.length > 0) {
            // Lưu dữ liệu toàn bộ bài báo
            allArticlesData = data;
        } else {
             console.error("API Lounge trả về dữ liệu rỗng hoặc có lỗi PHP.");
             articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Lỗi tải dữ liệu. Vui lòng kiểm tra fetch_lounge.php.</p>';
             return;
        }

        // Render sau khi tải thành công
        renderArticles(currentCategory, currentPage);

    } catch (error) {
        console.error("Lỗi khi tải dữ liệu Lounge:", error);
        articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Đã xảy ra lỗi kết nối Database hoặc PHP.</p>';
    }
}

// --- CHỨC NĂNG: TẠO CHUỖI HTML CHO THẺ BÀI LOUNGE ---
function createArticleCardHTML(article) {
    // Đường dẫn .php này đã ĐÚNG
    const detailUrl = `CC_Lounge_ChiTiet.php?id=${article.id}`; 
    
    const imageUrl = `../img_vid/${article.imageUrl.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="lounge-card"> 
            <img src="${imageUrl}" alt="${article.title}" class="lounge-image">
            <div class="lounge-overlay">
                <p class="lounge-meta">
                    ${article.date} | Bởi: ${article.author}
                </p>
                <h2 class="lounge-title">${article.title}</h2>
                <p class="lounge-description">${article.description}</p>
            </div>
        </a>
    `;
}

// --- CHỨC NĂNG: RENDER (LỌC, PHÂN TRANG VÀ HIỂN THỊ) BÀI BÁO ---
function renderArticles(categorySlug, page) {
    
    // [ĐÃ SỬA LỖI]
    // 1. Lấy giá trị Tiếng Việt (ví dụ: "Mẹo sử dụng") từ bản đồ
    const targetVietnameseCategory = CATEGORY_MAP[categorySlug];
    
    // 2. Lọc dữ liệu: So sánh category_slug (Tiếng Việt) từ PHP
    //    với giá trị Tiếng Việt mà chúng ta vừa dịch
    const filteredArticles = allArticlesData.filter(article => 
        categorySlug === 'all' || article.category_slug === targetVietnameseCategory
    );
    
    // Tính toán phân trang
    const totalPages = Math.ceil(filteredArticles.length / ARTICLES_PER_PAGE);
    const startIndex = (page - 1) * ARTICLES_PER_PAGE;
    const endIndex = startIndex + ARTICLES_PER_PAGE;
    const articlesToDisplay = filteredArticles.slice(startIndex, endIndex);

    if (articlesToDisplay.length > 0) {
        articlesGrid.innerHTML = articlesToDisplay.map(createArticleCardHTML).join('');
    } else {
        articlesGrid.innerHTML = '<p style="text-align: center;">Không tìm thấy bài viết nào.</p>';
    }

    // Render thanh phân trang
    renderPagination(totalPages, page);
}

// --- CHỨC NĂNG: RENDER THANH PHÂN TRANG ---
function renderPagination(totalPages, currentPage) {
    paginationBar.innerHTML = '';
    if (totalPages > 1) {
        // Nút Previous
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '<';
        prevBtn.className = 'page-btn';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => handlePageChange(currentPage - 1);
        paginationBar.appendChild(prevBtn);

        // Các nút số trang
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'page-btn';
            if (i === currentPage) {
                btn.classList.add('active');
            }
            btn.onclick = () => handlePageChange(i);
            paginationBar.appendChild(btn);
        }

        // Nút Next
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '>';
        nextBtn.className = 'page-btn';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => handlePageChange(currentPage + 1);
        paginationBar.appendChild(nextBtn);
    }
}

// --- CHỨC NĂNG: XỬ LÝ KHI THAY ĐỔI CATEGORY (LỌC) ---
function handleCategoryChange(newCategory) {
    currentCategory = newCategory; // newCategory là "tips", "recipe",...
    currentPage = 1;

    // Cập nhật trạng thái active của nút lọc
    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.category === newCategory) {
            btn.classList.add('active');
        }
    });

    // Render lại bài báo
    renderArticles(newCategory, currentPage);
}

// --- CHỨC NĂNG: XỬ LÝ KHI THAY ĐỔI TRANG ---
function handlePageChange(newPage) {
    currentPage = newPage;
    // Render lại bài báo
    renderArticles(currentCategory, currentPage);
    // Cuộn lên đầu trang
    window.scrollTo({ top: 0, behavior: 'smooth' }); 
}

// --- LẮNG NGHE SỰ KIỆN CLICK CHO NÚT LỌC ---
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        handleCategoryChange(btn.dataset.category);
    });
});

// --- KHỞI CHẠY TẢI DỮ LIỆU KHI TRANG LOAD ---
document.addEventListener('DOMContentLoaded', () => {
    fetchArticlesData(); 
});