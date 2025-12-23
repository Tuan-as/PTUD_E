// CHỨC NĂNG: LOGIC XỬ LÝ TRANG CHI TIẾT LOUNGE

// đường dẫn api lấy chi tiết (trong thư mục con fetchdata)
const DETAIL_API_URL = 'fetchdata/fetch_lounge_detail.php'; 

// CHỨC NĂNG: CHUYỂN ĐỔI TEXT SANG HTML (MARKDOWN CƠ BẢN)
function formatTextToHtml(text) {
    if (!text) return '';
    
    let formattedText = text;
    
    // chuyển đổi in đậm **text** -> <strong>text</strong>
    formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); 

    // chuyển đổi 2 lần xuống dòng -> đoạn văn mới
    formattedText = formattedText.replace(/\n\n/g, '</p><p>'); 
    
    // chuyển đổi 1 lần xuống dòng -> thẻ br
    formattedText = formattedText.replace(/\n/g, '<br>');

    return `<p>${formattedText}</p>`;
}

// CHỨC NĂNG: XỬ LÝ NỘI DUNG VÀ CHÈN ẢNH VÀO VỊ TRÍ
function processContentWithImages(content, article) {
    let finalContent = content;

    // bản đồ các từ khóa ảnh và url tương ứng
    const imageMap = [
        { key: 'ẢNH 1', url: article.img1 },
        { key: 'ẢNH 2', url: article.img2 },
        { key: 'ẢNH 3', url: article.img3 }
    ];

    imageMap.forEach((img) => {
        if (img.url) {
            // đường dẫn ảnh: từ store_pages ra root vào img_vid
            const imageUrl = `../img_vid/${img.url.split('/').pop()}`;
            const imageHtml = `<div class="content-image-wrapper"><img src="${imageUrl}" alt="Ảnh nội dung" class="content-image"></div>`;
            // thay thế từ khóa trong bài viết bằng thẻ img
            finalContent = finalContent.replace(img.key, imageHtml);
        }
    });
    
    // định dạng văn bản sau khi chèn ảnh
    return formatTextToHtml(finalContent);
}

// CHỨC NĂNG: KHỞI TẠO KHI LOAD TRANG
document.addEventListener('DOMContentLoaded', () => {
    // lấy id bài viết từ url
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    if (articleId && !isNaN(articleId)) {
        // gọi hàm lấy dữ liệu
        fetchArticleDetail(articleId);
    } else {
        // báo lỗi nếu id không hợp lệ
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID bài viết không hợp lệ.</h2>';
    }
});

// CHỨC NĂNG: TẢI DỮ LIỆU CHI TIẾT TỪ SERVER
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
             // xử lý lỗi từ server trả về
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy bài viết.'}</h2>`;
             return;
        }

        // hiển thị nội dung chi tiết
        renderArticleDetail(data);

    } catch (error) {
        console.error("Lỗi tải chi tiết:", error);
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi kết nối server.</h2>`;
    }
}

// CHỨC NĂNG: HIỂN THỊ NỘI DUNG LÊN GIAO DIỆN
function renderArticleDetail(article) {
    const detailContent = document.getElementById('detail-content');
    
    // xử lý nội dung văn bản và ảnh
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
    
    // cập nhật tiêu đề tab trình duyệt
    document.title = article.title;
}