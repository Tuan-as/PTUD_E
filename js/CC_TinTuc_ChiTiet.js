// file: CC_TinTuc_ChiTiet.js - LOGIC HOÀN CHỈNH

// Đường dẫn API (Đã chính xác)
const DETAIL_API_URL = '../php/fetch_news_detail.php'; 

// --- CHỨC NĂNG: ĐỊNH DẠNG TEXT THUẦN THÀNH HTML ---
function formatTextToHtml(text) {
    if (!text) return '';
    // Thay thế tất cả ký tự ngắt dòng (\n) bằng thẻ <br>
    return text.replaceAll('\n', '<br>'); 
}

// --- CHỨC NĂNG: XỬ LÝ KHI TRANG LOAD (LẤY ID) ---
document.addEventListener('DOMContentLoaded', () => {
    // Lấy ID từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    if (articleId && !isNaN(articleId)) {
        // Gọi hàm tải dữ liệu chi tiết
        fetchArticleDetail(articleId);
    } else {
        // Hiển thị lỗi nếu ID không hợp lệ
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID bài báo không hợp lệ hoặc bị thiếu.</h2>';
    }
});

// --- CHỨC NĂNG: TẢI DỮ LIỆU CHI TIẾT TỪ API ---
async function fetchArticleDetail(id) {
    // THÊM THAM SỐ NGẪU NHIÊN ĐỂ VÔ HIỆU HÓA CACHE
    const cacheBuster = new Date().getTime();
    
    try {
        // Gửi yêu cầu đến API PHP
        const response = await fetch(`${DETAIL_API_URL}?id=${id}&_=${cacheBuster}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (data.error || !data.id) {
             // Xử lý lỗi từ phía server (ví dụ: không tìm thấy ID)
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy bài báo này.'}</h2>`;
             return;
        }

        // Render nội dung chi tiết
        renderArticleDetail(data);

    } catch (error) {
        console.error("Lỗi khi tải chi tiết bài báo:", error);
        // Xử lý lỗi kết nối client/network
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Không thể kết nối hoặc tải dữ liệu chi tiết. Vui lòng kiểm tra fetch_news_detail.php.</h2>`;
    }
}

// --- CHỨC NĂNG: RENDER NỘI DUNG CHI TIẾT RA DOM ---
function renderArticleDetail(article) {
    const detailContent = document.getElementById('detail-content');
    
    // Áp dụng hàm định dạng cho nội dung chi tiết
    const formattedContent = formatTextToHtml(article.content); 
    
    // Xử lý đường dẫn ảnh (Đã chính xác)
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
    // Cập nhật tiêu đề trang web
    document.title = article.title;
}