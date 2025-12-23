// CHỨC NĂNG: LOGIC XỬ LÝ TRANG LOUNGE

// khai báo hằng số
const ARTICLES_PER_PAGE = 4;
// đường dẫn api đã cập nhật vào thư mục con fetchdata
const API_URL = 'fetchdata/fetch_lounge.php';  

// lấy các phần tử dom
const articlesGrid = document.getElementById('articles-grid');
const filterBtns = document.querySelectorAll('.filter-btn');
const paginationBar = document.getElementById('pagination-bar');

// biến trạng thái
let allArticlesData = [];
let currentCategory = 'all'; 
let currentPage = 1;

// BẢN ĐỒ CHUYỂN ĐỔI DANH MỤC (ANH -> VIỆT)
const CATEGORY_MAP = {
    'recipe': 'Công thức nấu ăn',
    'tips': 'Mẹo sử dụng',
    'eco': 'Sống xanh',
    'all': 'Tất cả' 
};

// CHỨC NĂNG: TẢI DỮ LIỆU TỪ API
async function fetchArticlesData() {
    try {
        const response = await fetch(API_URL);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (Array.isArray(data) && data.length > 0) {
            // lưu dữ liệu vào biến toàn cục
            allArticlesData = data;
        } else {
             // xử lý khi không có dữ liệu
             console.error("API Lounge trả về dữ liệu rỗng hoặc có lỗi PHP.");
             articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Lỗi tải dữ liệu.</p>';
             return;
        }

        // hiển thị danh sách bài viết
        renderArticles(currentCategory, currentPage);

    } catch (error) {
        console.error("Lỗi khi tải dữ liệu Lounge:", error);
        articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Lỗi kết nối.</p>';
    }
}

// CHỨC NĂNG: TẠO HTML CHO MỘT THẺ BÀI VIẾT
function createArticleCardHTML(article) {
    // đường dẫn đến trang chi tiết
    const detailUrl = `CC_Lounge_ChiTiet.php?id=${article.id}`; 
    
    // đường dẫn ảnh (từ store_pages đi ra root rồi vào img_vid)
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

// CHỨC NĂNG: LỌC VÀ HIỂN THỊ DANH SÁCH BÀI
function renderArticles(categorySlug, page) {
    // lấy tên danh mục tiếng việt từ bản đồ
    const targetVietnameseCategory = CATEGORY_MAP[categorySlug];
    
    // lọc bài viết theo danh mục
    const filteredArticles = allArticlesData.filter(article => 
        categorySlug === 'all' || article.category_slug === targetVietnameseCategory
    );
    
    // tính toán phân trang
    const totalPages = Math.ceil(filteredArticles.length / ARTICLES_PER_PAGE);
    const startIndex = (page - 1) * ARTICLES_PER_PAGE;
    const endIndex = startIndex + ARTICLES_PER_PAGE;
    const articlesToDisplay = filteredArticles.slice(startIndex, endIndex);

    // render html ra màn hình
    if (articlesToDisplay.length > 0) {
        articlesGrid.innerHTML = articlesToDisplay.map(createArticleCardHTML).join('');
    } else {
        articlesGrid.innerHTML = '<p style="text-align: center;">Không tìm thấy bài viết nào.</p>';
    }

    // render thanh phân trang
    renderPagination(totalPages, page);
}

// CHỨC NĂNG: TẠO THANH PHÂN TRANG
function renderPagination(totalPages, currentPage) {
    paginationBar.innerHTML = '';
    if (totalPages > 1) {
        // nút lùi
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '<';
        prevBtn.className = 'page-btn';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => handlePageChange(currentPage - 1);
        paginationBar.appendChild(prevBtn);

        // các nút số trang
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

        // nút tiến
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '>';
        nextBtn.className = 'page-btn';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => handlePageChange(currentPage + 1);
        paginationBar.appendChild(nextBtn);
    }
}

// CHỨC NĂNG: XỬ LÝ SỰ KIỆN ĐỔI DANH MỤC
function handleCategoryChange(newCategory) {
    currentCategory = newCategory;
    currentPage = 1;

    // cập nhật trạng thái active cho nút lọc
    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.category === newCategory) {
            btn.classList.add('active');
        }
    });

    // hiển thị lại danh sách
    renderArticles(newCategory, currentPage);
}

// CHỨC NĂNG: XỬ LÝ SỰ KIỆN ĐỔI TRANG
function handlePageChange(newPage) {
    currentPage = newPage;
    renderArticles(currentCategory, currentPage);
    // cuộn lên đầu trang
    window.scrollTo({ top: 0, behavior: 'smooth' }); 
}

// CHỨC NĂNG: KHỞI TẠO SỰ KIỆN CLICK
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        handleCategoryChange(btn.dataset.category);
    });
});

// CHỨC NĂNG: CHẠY KHI TRANG TẢI XONG
document.addEventListener('DOMContentLoaded', () => {
    fetchArticlesData(); 
});