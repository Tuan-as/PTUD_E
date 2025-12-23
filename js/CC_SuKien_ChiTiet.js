// CHỨC NĂNG: LOGIC XỬ LÝ TRANG CHI TIẾT SỰ KIỆN

// đường dẫn api tính từ thư mục store_pages
const DETAIL_API_URL = 'fetchdata/fetch_event_detail.php'; 

// CHỨC NĂNG: CHUYỂN ĐỔI MARKDOWN CƠ BẢN SANG HTML
function formatTextToHtml(text) {
    if (!text) return '';
    let formattedText = text;
    // chuyển **text** thành in đậm
    formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); 
    // chuyển 2 lần xuống dòng thành đoạn văn mới
    formattedText = formattedText.replace(/\n\n/g, '</p><p>'); 
    // chuyển 1 lần xuống dòng thành thẻ br
    formattedText = formattedText.replace(/\n/g, '<br>');
    
    return `<p>${formattedText}</p>`;
}

// CHỨC NĂNG: XỬ LÝ KHI TRANG LOAD ĐỂ LẤY ID TỪ URL
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    if (articleId && !isNaN(articleId)) {
        // gọi hàm tải dữ liệu
        fetchEventDetail(articleId);
    } else {
        // báo lỗi giao diện nếu thiếu id
        document.getElementById('detail-content').innerHTML = '<h2 style="color: red;">Lỗi: ID sự kiện không hợp lệ.</h2>';
    }
});

// CHỨC NĂNG: GỌI API LẤY CHI TIẾT SỰ KIỆN
async function fetchEventDetail(id) {
    // thêm tham số thời gian để tránh cache trình duyệt
    const cacheBuster = new Date().getTime();
    
    try {
        const response = await fetch(`${DETAIL_API_URL}?id=${id}&_=${cacheBuster}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (data.error || !data.id) {
             // xử lý lỗi từ server
             document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi: ${data.error || 'Không tìm thấy sự kiện.'}</h2>`;
             return;
        }

        // hiển thị dữ liệu
        renderEventDetail(data);

    } catch (error) {
        console.error("Lỗi khi tải chi tiết sự kiện:", error);
        document.getElementById('detail-content').innerHTML = `<h2 style="color: red;">Lỗi kết nối server.</h2>`;
    }
}

// CHỨC NĂNG: HIỂN THỊ NỘI DUNG CHI TIẾT RA GIAO DIỆN
function renderEventDetail(event) {
    const detailContent = document.getElementById('detail-content');
    
    // định dạng nội dung chính
    const formattedContent = formatTextToHtml(event.content); 
    
    // tạo class màu sắc dựa trên trạng thái (upcoming/past/current)
    const statusClass = `status-${event.status_slug}`;
    const statusTagHtml = `<span class="event-status ${statusClass}">${event.status_label}</span>`;

    // đường dẫn ảnh (đi ra root rồi vào img_vid)
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
    
    // cập nhật tiêu đề tab trình duyệt
    document.title = event.title;
}