// file: CC_Lounge_ChiTiet.js - LOGIC XỬ LÝ TRANG CHI TIẾT LOUNGE

// Đường dẫn API (Đã chính xác)
const DETAIL_API_URL = '../php/fetch_lounge_detail.php'; 

// --- CHỨC NĂNG: ĐỊNH DẠNG TEXT (MARKDOWN TO HTML) ---
function formatTextToHtml(text) {
    if (!text) return '';
    
    let formattedText = text;
    
    // 1. CHUYỂN ĐỔI MARKDOWN **text** thành <strong>text</strong> (In đậm)
    formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); 

    // 2. Chuyển đổi 2 lần ngắt dòng thành kết thúc đoạn </p><p>
    formattedText = formattedText.replace(/\n\n/g, '</p><p>'); 
    
    // 3. Chuyển đổi 1 lần ngắt dòng thành <br>
    formattedText = formattedText.replace(/\n/g, '<br>');

    // 4. Bọc toàn bộ trong thẻ <p>
    return `<p>${formattedText}</p>`;
}

// --- CHỨC NĂNG: XỬ LÝ NỘI DUNG VÀ CHÈN ẢNH VÀO VỊ TRÍ ---
function processContentWithImages(content, article) {
    let finalContent = content;

    // Định nghĩa map key và URL ảnh nội dung
    const imageMap = [
        { key: 'ẢNH 1', url: article.img1 },
        { key: 'ẢNH 2', url: article.img2 },
        { key: 'ẢNH 3', url: article.img3 }
    ];

    imageMap.forEach((img) => {
        if (img.url) {
            // Xử lý đường dẫn ảnh nội dung (Đã chính xác)
            const imageUrl = `../img_vid/${img.url.split('/').pop()}`;
            const imageHtml = `<div class="content-image-wrapper"><img src="${imageUrl}" alt="Ảnh nội dung" class="content-image"></div>`;
            // Thay thế từ khóa (Ví dụ: 'ẢNH 1') bằng HTML của ảnh
            finalContent = finalContent.replace(img.key, imageHtml);
        }
    });
    
    // Áp dụng định dạng văn bản (<p>, <br>, <strong>) sau khi chèn ảnh
    return formatTextToHtml(finalContent);
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
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID bài viết Lounge không hợp lệ hoặc bị thiếu.</h2>';
    }
});

// --- CHỨC NĂNG: TẢI DỮ LIỆU CHI TIẾT TỪ API ---
async function fetchArticleDetail(id) {
    // Thêm tham số chống cache
    const cacheBuster = new Date().getTime();
    
    try {
        // Gửi yêu cầu đến API PHP
        const response = await fetch(`${DETAIL_API_URL}?id=${id}&_=${cacheBuster}`);
        
        if (!response.ok) {
            // Hiển thị lỗi mạng nếu status không phải 200
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (data.error || !data.id) {
             // Xử lý lỗi từ phía server
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy bài viết Lounge này.'}</h2>`;
             return;
        }

        // Render nội dung chi tiết
        renderArticleDetail(data);

    } catch (error) {
        console.error("Lỗi khi tải chi tiết bài viết Lounge:", error);
        // Xử lý lỗi kết nối
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Không thể kết nối hoặc tải dữ liệu chi tiết Lounge. Vui lòng kiểm tra file PHP. (${error.message})</h2>`;
    }
}

// --- CHỨC NĂNG: RENDER NỘI DUNG CHI TIẾT RA DOM ---
function renderArticleDetail(article) {
    const detailContent = document.getElementById('detail-content');
    
    // Xử lý nội dung và chèn ảnh vào vị trí
    const finalFormattedContent = processContentWithImages(article.content, article); 

    detailContent.innerHTML = `
        <h1 class="article-title">${article.title}</h1>
        <p class="detail-meta">
            Danh mục: ${article.category} | Ngày đăng: ${article.date} | Tác giả: ${article.author}
        </p>
        
        <p class="article-description-intro">
            ${article.description}
        </p>
        
        <div class="full-article-content">
            ${finalFormattedContent} 
        </div>
    `;
    // Cập nhật tiêu đề trang web
    document.title = article.title;
}