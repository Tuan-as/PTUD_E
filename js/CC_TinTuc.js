// file: CC_TinTuc.js

// Hằng số cho phân trang
const ARTICLES_PER_PAGE = 9;
// Đường dẫn API (Đã Sửa: ra khỏi js/ rồi vào php/)
const API_URL = '../php/fetch_news.php';  

// Lấy các phần tử DOM
const articlesGrid = document.getElementById('articles-grid');
const filterBtns = document.querySelectorAll('.filter-btn');
const paginationBar = document.getElementById('pagination-bar');

// Biến trạng thái
let allArticlesData = [];
let currentCategory = 'all';
let currentPage = 1;

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
             console.error("API trả về dữ liệu rỗng hoặc không phải định dạng mong muốn.");
             articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Không thể tải dữ liệu bài báo. Vui lòng kiểm tra API PHP.</p>';
             return;
        }

        // Render ban đầu sau khi tải dữ liệu thành công
        renderArticles(currentCategory, currentPage);

    } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
        articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Đã xảy ra lỗi kết nối Database hoặc PHP.</p>';
    }
}

// --- CHỨC NĂNG: RENDER (LỌC, PHÂN TRANG VÀ HIỂN THỊ) BÀI BÁO ---
function renderArticles(category, page) {
    // Lọc bài báo theo category
    const filteredArticles = allArticlesData.filter(article => 
        category === 'all' || article.category === category
    );
    
    // Tính toán phân trang
    const totalPages = Math.ceil(filteredArticles.length / ARTICLES_PER_PAGE);
    const startIndex = (page - 1) * ARTICLES_PER_PAGE;
    const endIndex = startIndex + ARTICLES_PER_PAGE;
    // Cắt mảng để lấy bài báo hiển thị
    const articlesToDisplay = filteredArticles.slice(startIndex, endIndex);

    // Tạo HTML và chèn vào lưới
    articlesGrid.innerHTML = articlesToDisplay.map(createArticleCardHTML).join('');

    // Render thanh phân trang
    renderPagination(totalPages, page);
}

// --- CHỨC NĂNG: TẠO CHUỖI HTML CHO THẺ BÀI BÁO ---
function createArticleCardHTML(article) {
    const tagClass = article.category === 'product' ? 'tag-product' : 'tag-company';
    
    // [ĐÃ SỬA LỖI] Đổi .html thành .php (và bỏ ./)
    const detailUrl = `CC_TinTuc_ChiTiet.php?id=${article.id}`; 
    
    // Xử lý đường dẫn ảnh (Đã Sửa: ra khỏi js/ rồi vào img_vid/)
    const imageUrl = `../img_vid/${article.imageUrl.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="article-card"> 
            <div class="article-image-wrapper">
                <img src="${imageUrl}" alt="${article.title}" class="article-image">
            </div>
            <div class="article-content">
                <span class="article-tag ${tagClass}">${article.tag}</span>
                <h2 class="article-title">${article.title}</h2>
                <p class="article-description">${article.description}</p>
                <span class="article-date">${article.date}</span>
            </div>
        </a>
    `;
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
    currentCategory = newCategory;
    currentPage = 1; // Reset về trang 1

    // Cập nhật trạng thái active của nút lọc
    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.category === newCategory) {
            btn.classList.add('active');
        }
    });

    // Render lại bài báo
    renderArticles(currentCategory, currentPage);
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