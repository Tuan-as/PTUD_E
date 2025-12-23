// CHỨC NĂNG: LOGIC XỬ LÝ TRANG CHI TIẾT TIN TỨC

// đường dẫn api (tính từ store_pages vào fetchdata)
const DETAIL_API_URL = 'fetchdata/fetch_news_detail.php'; 

// CHỨC NĂNG: CHUYỂN ĐỔI KÝ TỰ XUỐNG DÒNG THÀNH HTML
function formatTextToHtml(text) {
    if (!text) return '';
    // thay thế \n bằng thẻ <br>
    return text.replaceAll('\n', '<br>'); 
}

// CHỨC NĂNG: XỬ LÝ KHI TRANG LOAD ĐỂ LẤY ID
document.addEventListener('DOMContentLoaded', () => {
    // lấy tham số id từ url
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    if (articleId && !isNaN(articleId)) {
        // gọi hàm lấy dữ liệu
        fetchArticleDetail(articleId);
    } else {
        // báo lỗi nếu thiếu id
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID bài báo không hợp lệ.</h2>';
    }
});

// CHỨC NĂNG: GỌI API LẤY CHI TIẾT
async function fetchArticleDetail(id) {
    // thêm tham số thời gian để tránh cache
    const cacheBuster = new Date().getTime();
    
    try {
        const response = await fetch(`${DETAIL_API_URL}?id=${id}&_=${cacheBuster}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (data.error || !data.id) {
             // xử lý lỗi từ server
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy bài báo.'}</h2>`;
             return;
        }

        // hiển thị nội dung
        renderArticleDetail(data);

    } catch (error) {
        console.error("Lỗi tải chi tiết:", error);
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi kết nối server.</h2>`;
    }
}

// CHỨC NĂNG: HIỂN THỊ DỮ LIỆU RA GIAO DIỆN
function renderArticleDetail(article) {
    const detailContent = document.getElementById('detail-content');
    
    // định dạng nội dung
    const formattedContent = formatTextToHtml(article.content); 
    
    // đường dẫn ảnh (đi ra root rồi vào img_vid)
    const imageUrl = `../img_vid/${article.imageUrl.split('/').pop()}`;

    detailContent.innerHTML = `
        <h1 class="article-title">${article.title}</h1>
        <p class="detail-meta">
            Danh mục: ${article.tag} | Ngày đăng: ${article.date}
        </p>
        
        <p class="article-description-intro">
            ${article.description}
        </p>
        
        <div class="detail-image-wrapper">
             <img src="${imageUrl}" alt="${article.title}">
        </div>
        
        <div class="full-article-content">
            ${formattedContent} 
        </div>
    `;
    
    // cập nhật tiêu đề tab
    document.title = article.title;
}