// file: CC_SuKien_ChiTiet.js - Logic tải và hiển thị chi tiết Sự Kiện

// Đường dẫn API (Đã chính xác)
const DETAIL_API_URL = '../php/fetch_event_detail.php'; 

// --- CHỨC NĂNG: ĐỊNH DẠNG TEXT (MARKDOWN TO HTML) ---
function formatTextToHtml(text) {
    if (!text) return '';
    let formattedText = text;
    // Chuyển **text** thành <strong>text</strong>
    formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); 
    // Chuyển 2 lần ngắt dòng thành kết thúc đoạn
    formattedText = formattedText.replace(/\n\n/g, '</p><p>'); 
    // Chuyển 1 lần ngắt dòng thành <br>
    formattedText = formattedText.replace(/\n/g, '<br>');
    // Bọc toàn bộ trong thẻ <p>
    return `<p>${formattedText}</p>`;
}

// --- CHỨC NĂNG: XỬ LÝ KHI TRANG LOAD (LẤY ID) ---
document.addEventListener('DOMContentLoaded', () => {
    // Lấy ID từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    if (articleId && !isNaN(articleId)) {
        // Gọi hàm tải dữ liệu chi tiết
        fetchEventDetail(articleId);
    } else {
        // Hiển thị lỗi nếu ID không hợp lệ
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID sự kiện không hợp lệ hoặc bị thiếu.</h2>';
    }
});

// --- CHỨC NĂNG: TẢI DỮ LIỆU CHI TIẾT TỪ API ---
async function fetchEventDetail(id) {
    // Thêm tham số chống cache
    const cacheBuster = new Date().getTime();
    
    try {
        // Gửi yêu cầu đến API PHP
        const response = await fetch(`${DETAIL_API_URL}?id=${id}&_=${cacheBuster}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (data.error || !data.id) {
             // Xử lý lỗi từ phía server
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy sự kiện này.'}</h2>`;
             return;
        }

        // Render nội dung chi tiết
        renderEventDetail(data);

    } catch (error) {
        console.error("Lỗi khi tải chi tiết sự kiện:", error);
        // Xử lý lỗi kết nối
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Không thể kết nối hoặc tải dữ liệu chi tiết Sự kiện.</h2>`;
    }
}

// --- CHỨC NĂNG: RENDER NỘI DUNG CHI TIẾT RA DOM ---
function renderEventDetail(event) {
    const detailContent = document.getElementById('detail-content');
    // Định dạng nội dung chi tiết
    const formattedContent = formatTextToHtml(event.content); 
    
    // Lấy class slug và nhãn trạng thái
    const statusClass = `status-${event.status_slug}`;
    const statusTagHtml = `<span class="event-status ${statusClass}">${event.status_label}</span>`;

    // Xử lý đường dẫn ảnh chính (Đã chính xác)
    const imageUrl = `../img_vid/${event.imageUrl.split('/').pop()}`;


    detailContent.innerHTML = `
        <h1 class="article-title">${event.title}</h1>
        <p class="detail-meta">
            ${statusTagHtml} | 
            Thời gian: ${event.date_range} | 
            Địa điểm: ${event.location}
        </p>
        
        <p class="article-description-intro">
            ${event.description}
        </p>
        
        <div class="detail-image-wrapper">
             <img src="${imageUrl}" alt="${event.title}">
        </div>
        
        <div class="full-article-content">
            ${formattedContent} 
        </div>
    `;
    // Cập nhật tiêu đề trang web
    document.title = event.title;
}