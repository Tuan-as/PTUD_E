// CHỨC NĂNG: LOGIC XỬ LÝ TRANG DANH SÁCH TIN TỨC

// hằng số phân trang
const ARTICLES_PER_PAGE = 9;
// đường dẫn api (tính từ store_pages vào fetchdata)
const API_URL = 'fetchdata/fetch_news.php';  

// lấy các phần tử dom
const articlesGrid = document.getElementById('articles-grid');
const filterBtns = document.querySelectorAll('.filter-btn');
const paginationBar = document.getElementById('pagination-bar');

// biến trạng thái
let allArticlesData = [];
let currentCategory = 'all';
let currentPage = 1;

// CHỨC NĂNG: TẢI DỮ LIỆU TỪ SERVER
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
             console.error("API trả về dữ liệu rỗng.");
             articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Không tìm thấy bài báo nào.</p>';
             return;
        }

        // hiển thị dữ liệu lần đầu
        renderArticles(currentCategory, currentPage);

    } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
        articlesGrid.innerHTML = '<p style="text-align: center; color: red;">Lỗi kết nối.</p>';
    }
}

// CHỨC NĂNG: LỌC, PHÂN TRANG VÀ HIỂN THỊ
function renderArticles(category, page) {
    // lọc bài báo theo category
    const filteredArticles = allArticlesData.filter(article => 
        category === 'all' || article.category === category
    );
    
    // tính toán phân trang
    const totalPages = Math.ceil(filteredArticles.length / ARTICLES_PER_PAGE);
    const startIndex = (page - 1) * ARTICLES_PER_PAGE;
    const endIndex = startIndex + ARTICLES_PER_PAGE;
    
    // lấy danh sách bài cho trang hiện tại
    const articlesToDisplay = filteredArticles.slice(startIndex, endIndex);

    // tạo html và chèn vào lưới
    articlesGrid.innerHTML = articlesToDisplay.map(createArticleCardHTML).join('');

    // hiển thị thanh phân trang
    renderPagination(totalPages, page);
}

// CHỨC NĂNG: TẠO MÃ HTML CHO THẺ BÀI BÁO
function createArticleCardHTML(article) {
    const tagClass = article.category === 'product' ? 'tag-product' : 'tag-company';
    
    // đường dẫn đến trang chi tiết (cùng thư mục store_pages)
    const detailUrl = `CC_TinTuc_ChiTiet.php?id=${article.id}`; 
    
    // đường dẫn ảnh (đi ra root rồi vào img_vid)
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

// CHỨC NĂNG: TẠO THANH PHÂN TRANG
function renderPagination(totalPages, currentPage) {
    paginationBar.innerHTML = '';
    if (totalPages > 1) {
        // nút lùi (prev)
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

        // nút tiến (next)
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '>';
        nextBtn.className = 'page-btn';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => handlePageChange(currentPage + 1);
        paginationBar.appendChild(nextBtn);
    }
}

// CHỨC NĂNG: XỬ LÝ KHI BẤM NÚT LỌC
function handleCategoryChange(newCategory) {
    currentCategory = newCategory;
    currentPage = 1; 

    // cập nhật trạng thái active
    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.category === newCategory) {
            btn.classList.add('active');
        }
    });

    renderArticles(currentCategory, currentPage);
}

// CHỨC NĂNG: XỬ LÝ KHI CHUYỂN TRANG
function handlePageChange(newPage) {
    currentPage = newPage;
    renderArticles(currentCategory, currentPage);
    // cuộn lên đầu trang
    window.scrollTo({ top: 0, behavior: 'smooth' }); 
}

// CHỨC NĂNG: GẮN SỰ KIỆN CLICK CHO NÚT LỌC
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        handleCategoryChange(btn.dataset.category);
    });
});

// CHỨC NĂNG: KHỞI TẠO KHI TRANG TẢI XONG
document.addEventListener('DOMContentLoaded', () => {
    fetchArticlesData(); 
});