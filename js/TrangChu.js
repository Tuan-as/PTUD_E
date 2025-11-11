// --- CÁC ĐƯỜNG DẪN API ---
// (Dùng chung cho cả Trang Chủ và Trang Câu Chuyện)

// API cho Tin Tức (Lấy 6 mục cho slider)
const NEWS_API_6 = '../php/fetch_story_news.php'; 
// API cho Sự Kiện (Lấy 6 mục cho slider)
const EVENTS_API_6 = '../php/fetch_story_events.php';
// API cho Lounge (Lấy 5 mục cho slider)
const LOUNGE_API_5 = '../php/fetch_homepage_lounge.php'; 
 

// --- KHỞI TẠO KHI TẢI TRANG (DOMContentLoaded) ---
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Khởi tạo các chức năng CHỈ CÓ trên Trang Chủ
    // Kiểm tra sự tồn tại của các phần tử đặc trưng của Trang Chủ
    if (document.querySelector('.product-slider')) {
        setupProductSlider(); // Khởi tạo slider banner sản phẩm
    }
    if (document.querySelector('.about-us-menu-map-list')) {
        setupAboutUsHover(); // Khởi tạo hiệu ứng hover cho khối "Giới thiệu"
    }
    
    // 2. Tải nội dung động (Tin Tức, Lounge, Sự Kiện)
    // Các hàm này sẽ tự kiểm tra ID tồn tại, nên có thể gọi ở cả 2 trang
    // Trang Chủ sẽ dùng (news-section), Trang Câu Chuyện dùng (news-section-story)

    // Tải Tin Tức (dùng chung API 6 mục)
    fetchAndRenderNews('news-section', NEWS_API_6);       // Thử tải vào ID của Trang Chủ
    fetchAndRenderNews('news-section-story', NEWS_API_6); // Thử tải vào ID của Trang Câu Chuyện

    // Tải Lounge (dùng chung API 5 mục)
    fetchAndRenderLounge('lounge-section', LOUNGE_API_5);       // Thử tải vào ID của Trang Chủ
    fetchAndRenderLounge('lounge-section-story', LOUNGE_API_5); // Thử tải vào ID của Trang Câu Chuyện

    // Tải Sự Kiện (dùng chung API 6 mục)
    fetchAndRenderEvents('events-section', EVENTS_API_6);       // Thử tải vào ID của Trang Chủ
    fetchAndRenderEvents('events-section-story', EVENTS_API_6); // Thử tải vào ID của Trang Câu Chuyện
});


// =================================================================
// CHỨC NĂNG 1: KHỞI TẠO SLIDER BANNER SẢN PHẨM (TRANG CHỦ)
// =================================================================
function setupProductSlider() {
    // Lấy các phần tử DOM
    const slider = document.querySelector('.product-slider');
    const prevBtn = document.querySelector('.prev-control');
    const nextBtn = document.querySelector('.next-control');
    const dotsContainer = document.querySelector('.slider-dots');
    const slides = document.querySelectorAll('.product-banner-item');
    
    // Thoát nếu không tìm thấy phần tử (để không chạy ở trang khác)
    if (!slider || !prevBtn || !nextBtn || !dotsContainer || slides.length === 0) return;
    
    const totalSlides = slides.length;
    let currentIndex = 0;

    // Hàm con: Cập nhật dấu chấm active
    function updateDots() {
        const dots = dotsContainer.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    // Hàm con: Cuộn đến slide theo index
    function scrollToSlide(index) {
        if (index >= 0 && index < totalSlides) {
            currentIndex = index;
            slider.scrollLeft = currentIndex * slider.offsetWidth; 
            updateDots();
        }
    }

    // Gán sự kiện cho nút Next
    nextBtn.addEventListener('click', () => {
        const newIndex = (currentIndex + 1) % totalSlides;
        scrollToSlide(newIndex);
    });

    // Gán sự kiện cho nút Prev
    prevBtn.addEventListener('click', () => {
        const newIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        scrollToSlide(newIndex);
    });

    // Gán sự kiện cho các dấu chấm
    dotsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('dot')) {
            const index = parseInt(e.target.dataset.slideIndex);
            scrollToSlide(index);
        }
    });

    // Cập nhật dấu chấm khi người dùng tự cuộn (trên mobile)
    slider.addEventListener('scroll', () => {
        const scrollPosition = slider.scrollLeft;
        const slideWidth = slider.offsetWidth;
        const newIndex = Math.round(scrollPosition / slideWidth);

        if (newIndex !== currentIndex) {
            currentIndex = newIndex;
            updateDots();
        }
    });

    // Khởi tạo dấu chấm cho slide đầu tiên
    updateDots(); 
}


// =================================================================
// CHỨC NĂNG 2: HIỆU ỨNG HOVER CHO KHỐI "GIỚI THIỆU" (TRANG CHỦ)
// =================================================================
function setupAboutUsHover() {
    // Lấy các phần tử DOM
    const aboutUsMenuItems = document.querySelectorAll('.about-us-menu-map-list li');
    const aboutUsBackground = document.querySelector('.about-us-image-map-card'); 

    // Thoát nếu không tìm thấy (để không chạy ở trang khác)
    if (!aboutUsMenuItems.length || !aboutUsBackground) return;

    // Lấy ảnh mặc định từ mục menu đầu tiên
    const defaultImage = '../img_vid/' + aboutUsMenuItems[0].dataset.image.split('/').pop(); 
    let currentImage = defaultImage;
    
    // Đặt ảnh nền mặc định
    if (defaultImage) {
        aboutUsBackground.style.backgroundImage = `url('${defaultImage}')`;
    }

    // Gán sự kiện cho từng mục menu
    aboutUsMenuItems.forEach(item => {
        // Khi di chuột vào: đổi ảnh nền
        item.addEventListener('mouseenter', () => {
            const newImagePath = item.dataset.image;
            const newImage = `../img_vid/${newImagePath.split('/').pop()}`;
            if (newImage) {
                aboutUsBackground.style.backgroundImage = `url('${newImage}')`; 
                currentImage = newImage;
            }
        });
        
        // Khi di chuột ra: trả về ảnh mặc định
        item.addEventListener('mouseleave', () => {
            if (currentImage !== defaultImage) {
                 aboutUsBackground.style.backgroundImage = `url('${defaultImage}')`;
                 currentImage = defaultImage;
            }
        });
    });
}


// =================================================================
// CÁC HÀM TẠO CARD HTML (DÙNG CHUNG)
// =================================================================

// CHỨC NĂNG 3: TẠO HTML CHO 1 THẺ TIN TỨC
function createNewsCard(article) {
    // Lấy slug (product/company) để chọn màu tag
    const tagSlug = article.category_slug === 'product' ? 'product' : 'company';
    // Tạo link chi tiết
    const detailUrl = `CC_TinTuc_ChiTiet.php?id=${article.id}`;
    // Đảm bảo đường dẫn ảnh đúng
    const imageUrl = `../img_vid/${article.imageUrl.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="grid-item-card">
            <div class="image-wrapper">
                <img src="${imageUrl}" alt="${article.title}" class="item-image">
            </div>
            <div class="item-content">
                <span class="article-tag tag-${tagSlug}">${article.tag}</span>
                <h3 class="item-title">${article.title}</h3>
                <p class="item-description">${article.description.substring(0, 100)}...</p>
                <p class="item-meta">${article.date}</p>
            </div>
        </a>
    `;
}

// CHỨC NĂNG 4: TẠO HTML CHO 1 BÀI VIẾT LOUNGE
function createLoungeItem(article) {
    const detailUrl = `CC_Lounge_ChiTiet.php?id=${article.id}`;
    const imageUrl = `../img_vid/${article.imageUrl.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="lounge-item">
            <img src="${imageUrl}" alt="${article.title}">
            <div class="lounge-caption">
                <h3>${article.title}</h3>
                <p>${article.description.substring(0, 80)}...</p>
                <p class="lounge-meta">${article.date} | ${article.author}</p>
            </div>
        </a>
    `;
}

// CHỨC NĂNG 5: TẠO HTML CHO 1 THẺ SỰ KIỆN
function createEventCard(event) {
    const detailUrl = `CC_SuKien_ChiTiet.php?id=${event.id}`;
    // Lấy slug (past/current/upcoming) để chọn màu tag
    const statusSlug = event.status_slug || 'upcoming'; 
    const statusClass = `status-${statusSlug.toLowerCase().replace(/ /g, '-')}`; 
    // Lấy nhãn (Đã/Đang/Sắp diễn ra)
    const statusLabel = event.status_label || 'Sắp diễn ra'; 
    const imageUrl = `../img_vid/${event.image_url.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="grid-item-card">
            <div class="image-wrapper">
                <img src="${imageUrl}" alt="${event.title}" class="item-image">
            </div>
            <div class="item-content">
                <span class="event-status ${statusClass}">${statusLabel}</span>
                <h3 class="item-title">${event.title}</h3>
                <p class="item-description">${event.description || 'Thông tin chi tiết đang được cập nhật...'}</p>
                <p class="item-meta">${event.date_range || 'Chưa xác định'}</p>
            </div>
        </a>
    `;
}


// =================================================================
// CÁC HÀM TẢI DỮ LIỆU (FETCH API - DÙNG CHUNG)
// =================================================================

// CHỨC NĂNG 6: TẢI VÀ HIỂN THỊ TIN TỨC
async function fetchAndRenderNews(elementId, apiUrl) {
    // Tìm phần tử mục tiêu (ví dụ: 'news-section' hoặc 'news-section-story')
    const targetElement = document.getElementById(elementId);
    // Bỏ qua nếu không tìm thấy ID (ví dụ: đang ở Trang Chủ thì 'news-section-story' là null)
    if (!targetElement) return; 

    try {
        // Gọi API
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status} (Không tìm thấy file ${apiUrl})`);
        }
        const data = await response.json();

        // Nếu có dữ liệu, chèn HTML
        if (Array.isArray(data) && data.length > 0) {
            targetElement.innerHTML = data.map(createNewsCard).join('');
        } else {
            // Nếu không có, báo rỗng
            targetElement.innerHTML = '<p style="text-align: center;">Không có tin tức nào để hiển thị.</p>';
        }
    } catch (error) {
        // Nếu lỗi (ví dụ: file PHP sai), báo lỗi
        console.error(`Lỗi tải Tin Tức vào #${elementId}:`, error);
        targetElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu. (Chi tiết: ${error.message}).</p>`;
    }
}

// CHỨC NĂNG 7: TẢI VÀ HIỂN THỊ LOUNGE
async function fetchAndRenderLounge(elementId, apiUrl) {
    const sliderElement = document.getElementById(elementId);
    if (!sliderElement) return; // Bỏ qua nếu không tìm thấy ID

    try {
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status} (Không tìm thấy file ${apiUrl})`);
        }
        const data = await response.json();

        if (Array.isArray(data) && data.length > 0) {
            sliderElement.innerHTML = data.map(createLoungeItem).join('');
        } else {
             sliderElement.innerHTML = '<p style="text-align: center;">Không có bài viết Lounge nào để hiển thị.</p>';
        }
    } catch (error) {
        console.error(`Lỗi tải Lounge vào #${elementId}:`, error);
        sliderElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu. (Chi tiết: ${error.message}).</p>`;
    }
}

// CHỨC NĂNG 8: TẢI VÀ HIỂN THỊ SỰ KIỆN
async function fetchAndRenderEvents(elementId, apiUrl) {
    const targetElement = document.getElementById(elementId);
    if (!targetElement) return; // Bỏ qua nếu không tìm thấy ID
        
    try {
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status} (Không tìm thấy file ${apiUrl})`);
        }
        const data = await response.json();
        
        if (Array.isArray(data) && data.length > 0) {
            targetElement.innerHTML = data.map(createEventCard).join('');
        } else {
            targetElement.innerHTML = '<p style="text-align: center;">Hiện chưa có sự kiện nào.</p>';
        }
    } catch (error) {
         console.error(`Lỗi tải Sự kiện vào #${elementId}:`, error);
         targetElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu. (Chi tiết: ${error.message}).</p>`;
    }
}