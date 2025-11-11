// file: CC_SuKien.js - LOGIC XỬ LÝ TRANG SỰ KIỆN

const EVENTS_PER_PAGE = 6; 
const API_URL = '../php/fetch_events.php';  

// Lấy các phần tử DOM
const eventsGrid = document.getElementById('events-grid'); 
const filterBtns = document.querySelectorAll('.filter-btn');
const paginationBar = document.getElementById('pagination-bar');

// Biến trạng thái
let allEventsData = [];
let currentStatus = 'all'; // Trạng thái lọc hiện tại
let currentPage = 1;

// --- CHỨC NĂNG: TẢI DỮ LIỆU SỰ KIỆN TỪ API ---
async function fetchEventsData() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json(); 
        
        if (Array.isArray(data) && data.length > 0) {
            allEventsData = data;
        } else {
             eventsGrid.innerHTML = '<p style="text-align: center; color: red;">Không có sự kiện nào được tìm thấy.</p>';
             return;
        }

        renderEvents(currentStatus, currentPage);

    } catch (error) {
        console.error("Lỗi khi tải dữ liệu Sự kiện:", error);
        eventsGrid.innerHTML = '<p style="text-align: center; color: red;">Đã xảy ra lỗi kết nối Database hoặc PHP.</p>';
    }
}

// --- CHỨC NĂNG: TẠO CHUỖI HTML CHO THẺ SỰ KIỆN ---
function createEventCardHTML(event) {
    const statusClass = `status-${event.status_slug}`;
    
    // Đường dẫn .php này đã ĐÚNG
    const detailUrl = `CC_SuKien_ChiTiet.php?id=${event.id}`;
    
    const imageUrl = `../img_vid/${event.image_url.split('/').pop()}`;

    return `
        <a href="${detailUrl}" class="event-card"> 
            <div class="event-image-wrapper">
                <img src="${imageUrl}" alt="${event.title}" class="event-image">
            </div>
            <div class="event-content">
                <span class="event-status ${statusClass}">${event.status_label}</span>
                <h2 class="event-title">${event.title}</h2>
                <p class="event-description">${event.description.substring(0, 100)}...</p>
                <div class="event-meta">
                    <p>Thời gian: ${event.date_range}</p>
                    <p>Địa điểm: ${event.location}</p>
                </div>
            </div>
        </a>
    `;
}

// --- CHỨC NĂNG: RENDER (LỌC VÀ PHÂN TRANG) SỰ KIỆN ---
function renderEvents(status, page) {
    
    const filteredEvents = allEventsData.filter(event => 
        status === 'all' || event.status_slug === status
    );
    
    const totalPages = Math.ceil(filteredEvents.length / EVENTS_PER_PAGE);
    const startIndex = (page - 1) * EVENTS_PER_PAGE;
    const endIndex = startIndex + EVENTS_PER_PAGE;
    const eventsToDisplay = filteredEvents.slice(startIndex, endIndex);

    eventsGrid.innerHTML = eventsToDisplay.map(createEventCardHTML).join('');

    renderPagination(totalPages, page);
}

// --- CHỨC NĂNG: XỬ LÝ KHI THAY ĐỔI TRẠNG THÁI LỌC ---
function handleStatusChange(newStatus) {
    currentStatus = newStatus;
    currentPage = 1; 

    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.status === newStatus) {
            btn.classList.add('active');
        }
    });

    renderEvents(newStatus, currentPage);
}

// --- CHỨC NĂNG: RENDER THANH PHÂN TRANG ---
// [ĐÃ SỬA LỖI] Bổ sung code bị thiếu ở đây
function renderPagination(totalPages, currentPage) {
    paginationBar.innerHTML = '';
    if (totalPages > 1) {
        // Nút Previous
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '<';
        prevBtn.className = 'page-btn';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => handlePageChange(currentPage - 1);
        paginationBar.appendChild(prevBtn);

        // Các nút số trang
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

        // Nút Next
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '>';
        nextBtn.className = 'page-btn';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => handlePageChange(currentPage + 1);
        paginationBar.appendChild(nextBtn);
    }
}

// --- CHỨC NĂNG: XỬ LÝ KHI THAY ĐỔI TRANG ---
function handlePageChange(newPage) {
    currentPage = newPage;
    renderEvents(currentStatus, currentPage);
    window.scrollTo({ top: 0, behavior: 'smooth' }); 
}

// --- GẮN SỰ KIỆN CHO CÁC NÚT LỌC ---
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        handleStatusChange(btn.dataset.status);
    });
});

// --- KHỞI TẠO DỮ LIỆU KHI TRANG LOAD ---
document.addEventListener('DOMContentLoaded', () => {
    fetchEventsData(); 
});