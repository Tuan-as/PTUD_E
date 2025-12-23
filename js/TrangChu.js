// CÁC ĐƯỜNG DẪN API (Đã cập nhật folder fetchdata)
// (Dùng chung cho cả Trang Chủ và Trang Câu Chuyện)

// API cho Tin Tức (Sử dụng API chung fetch_news.php)
const NEWS_API_6 = 'fetchdata/fetch_news.php'; 
// API cho Sự Kiện (Sử dụng API chung fetch_events.php)
const EVENTS_API_6 = 'fetchdata/fetch_events.php';
// API cho Lounge (Lấy 5 mục cho slider)
const LOUNGE_API_5 = 'fetchdata/fetch_homepage_lounge.php'; 
 
// CHỨC NĂNG: KHỞI TẠO KHI TẢI TRANG
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Khởi tạo các chức năng CHỈ CÓ trên Trang Chủ
    if (document.querySelector('.product-slider')) {
        setupProductSlider(); 
    }
    if (document.querySelector('.about-us-menu-map-list')) {
        setupAboutUsHover(); 
    }
    
    // 2. Tải nội dung động (Tin Tức, Lounge, Sự Kiện)
    // Tải Tin Tức
    fetchAndRenderNews('news-section', NEWS_API_6);      
    fetchAndRenderNews('news-section-story', NEWS_API_6); 

    // Tải Lounge
    fetchAndRenderLounge('lounge-section', LOUNGE_API_5);      
    fetchAndRenderLounge('lounge-section-story', LOUNGE_API_5); 

    // Tải Sự Kiện
    fetchAndRenderEvents('events-section', EVENTS_API_6);      
    fetchAndRenderEvents('events-section-story', EVENTS_API_6); 
});

// CHỨC NĂNG: CẤU HÌNH SLIDER BANNER SẢN PHẨM
function setupProductSlider() {
    const slider = document.querySelector('.product-slider');
    const prevBtn = document.querySelector('.prev-control');
    const nextBtn = document.querySelector('.next-control');
    const dotsContainer = document.querySelector('.slider-dots');
    const slides = document.querySelectorAll('.product-banner-item');
    
    if (!slider || !prevBtn || !nextBtn || !dotsContainer || slides.length === 0) return;
    
    const totalSlides = slides.length;
    let currentIndex = 0;

    function updateDots() {
        const dots = dotsContainer.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function scrollToSlide(index) {
        if (index >= 0 && index < totalSlides) {
            currentIndex = index;
            slider.scrollLeft = currentIndex * slider.offsetWidth; 
            updateDots();
        }
    }

    nextBtn.addEventListener('click', () => {
        const newIndex = (currentIndex + 1) % totalSlides;
        scrollToSlide(newIndex);
    });

    prevBtn.addEventListener('click', () => {
        const newIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        scrollToSlide(newIndex);
    });

    dotsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('dot')) {
            const index = parseInt(e.target.dataset.slideIndex);
            scrollToSlide(index);
        }
    });

    slider.addEventListener('scroll', () => {
        const scrollPosition = slider.scrollLeft;
        const slideWidth = slider.offsetWidth;
        const newIndex = Math.round(scrollPosition / slideWidth);

        if (newIndex !== currentIndex) {
            currentIndex = newIndex;
            updateDots();
        }
    });

    updateDots(); 
}

// CHỨC NĂNG: HIỆU ỨNG HOVER CHO KHỐI GIỚI THIỆU
function setupAboutUsHover() {
    const aboutUsMenuItems = document.querySelectorAll('.about-us-menu-map-list li');
    const aboutUsBackground = document.querySelector('.about-us-image-map-card'); 

    if (!aboutUsMenuItems.length || !aboutUsBackground) return;

    // đường dẫn ảnh tương đối (../img_vid/)
    const defaultImage = '../img_vid/' + aboutUsMenuItems[0].dataset.image.split('/').pop(); 
    let currentImage = defaultImage;
    
    if (defaultImage) {
        aboutUsBackground.style.backgroundImage = `url('${defaultImage}')`;
    }

    aboutUsMenuItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            const newImagePath = item.dataset.image;
            const newImage = `../img_vid/${newImagePath.split('/').pop()}`;
            if (newImage) {
                aboutUsBackground.style.backgroundImage = `url('${newImage}')`; 
                currentImage = newImage;
            }
        });
        
        item.addEventListener('mouseleave', () => {
            if (currentImage !== defaultImage) {
                 aboutUsBackground.style.backgroundImage = `url('${defaultImage}')`;
                 currentImage = defaultImage;
            }
        });
    });
}

// CHỨC NĂNG: TẠO HTML CARD TIN TỨC
function createNewsCard(article) {
    const tagSlug = article.category === 'product' ? 'product' : 'company';
    const detailUrl = `CC_TinTuc_ChiTiet.php?id=${article.id}`;
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

// CHỨC NĂNG: TẠO HTML CARD LOUNGE
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

// CHỨC NĂNG: TẠO HTML CARD SỰ KIỆN
function createEventCard(event) {
    const detailUrl = `CC_SuKien_ChiTiet.php?id=${event.id}`;
    const statusSlug = event.status_slug || 'upcoming'; 
    const statusClass = `status-${statusSlug.toLowerCase().replace(/ /g, '-')}`; 
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


// CHỨC NĂNG: TẢI VÀ HIỂN THỊ TIN TỨC
async function fetchAndRenderNews(elementId, apiUrl) {
    const targetElement = document.getElementById(elementId);
    if (!targetElement) return; 

    try {
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();

        // hiển thị 6 bài đầu tiên
        if (Array.isArray(data) && data.length > 0) {
            const limitData = data.slice(0, 6); 
            targetElement.innerHTML = limitData.map(createNewsCard).join('');
        } else {
            targetElement.innerHTML = '<p style="text-align: center;">Không có tin tức nào.</p>';
        }
    } catch (error) {
        console.error(`Lỗi tải Tin Tức:`, error);
        targetElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu.</p>`;
    }
}

// CHỨC NĂNG: TẢI VÀ HIỂN THỊ LOUNGE
async function fetchAndRenderLounge(elementId, apiUrl) {
    const sliderElement = document.getElementById(elementId);
    if (!sliderElement) return; 

    try {
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();

        if (Array.isArray(data) && data.length > 0) {
            sliderElement.innerHTML = data.map(createLoungeItem).join('');
        } else {
             sliderElement.innerHTML = '<p style="text-align: center;">Không có bài viết Lounge nào.</p>';
        }
    } catch (error) {
        console.error(`Lỗi tải Lounge:`, error);
        sliderElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu.</p>`;
    }
}

// CHỨC NĂNG: TẢI VÀ HIỂN THỊ SỰ KIỆN
async function fetchAndRenderEvents(elementId, apiUrl) {
    const targetElement = document.getElementById(elementId);
    if (!targetElement) return; 
        
    try {
        const response = await fetch(apiUrl); 
        if (!response.ok) { 
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        
        // hiển thị 6 sự kiện đầu tiên
        if (Array.isArray(data) && data.length > 0) {
             const limitData = data.slice(0, 6);
            targetElement.innerHTML = limitData.map(createEventCard).join('');
        } else {
            targetElement.innerHTML = '<p style="text-align: center;">Hiện chưa có sự kiện nào.</p>';
        }
    } catch (error) {
         console.error(`Lỗi tải Sự kiện:`, error);
         targetElement.innerHTML = `<p style="text-align: center; color: red;">Lỗi tải dữ liệu.</p>`;
    }
}