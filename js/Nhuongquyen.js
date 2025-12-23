/* DỮ LIỆU CỬA HÀNG CHO SLIDER */
/* DỮ LIỆU CỬA HÀNG CHO SLIDER */
/* Note: Đã cập nhật thêm tiền tố NQ_ vào tên file để khớp với thư mục img_vid */
const storeData = [
    { name: "Cửa hàng LocknLock Thống Nhất Mới (1)", image: "../img_vid/NQ_Thongnhatmoi(1).jpg" },
    { name: "Cửa hàng LocknLock Thống Nhất Mới (2)", image: "../img_vid/NQ_Thongnhatmoi(2).jpg" },
    { name: "Cửa hàng LocknLock Thống Nhất Mới (3)", image: "../img_vid/NQ_Thongnhatmoi(3).jpg" },
    { name: "Cửa hàng LocknLock Vinhomes Q9 (1)", image: "../img_vid/NQ_VinhomesQ9(1).jpg" },
    { name: "Cửa hàng LocknLock Vinhomes Q9 (2)", image: "../img_vid/NQ_VinhomesQ9(2).jpg" },
    { name: "Cửa hàng LocknLock Vinhomes Q9 (3)", image: "../img_vid/NQ_VinhomesQ9(3).jpg" },
    { name: "Cửa hàng LocknLock Melody Vũng Tàu", image: "../img_vid/NQ_MelodyVungTau.jpg" },
    { name: "Cửa hàng LocknLock GO! Buôn Mê Thuật (1)", image: "../img_vid/NQ_GoBuonMethuat(1).jpg" },
    { name: "Cửa hàng LocknLock GO! Buôn Mê Thuật (2)", image: "../img_vid/NQ_GoBuonMeThuat(2).jpg" },
    { name: "Cửa hàng LocknLock Đà Nẵng", image: "../img_vid/NQ_DaNang.jpg" },
    { name: "Cửa hàng LocknLock Quảng Ngãi", image: "../img_vid/NQ_QuangNgai.jpg" },
];

/* SỰ KIỆN: KHỞI TẠO SLIDER KHI DOM LOAD */
document.addEventListener("DOMContentLoaded", function() {
    const sliderContent = document.getElementById("sliderContent");
    const prevArrow = document.getElementById("prevArrow");
    const nextArrow = document.getElementById("nextArrow");

    // Render dữ liệu ra HTML
    if (sliderContent) {
        sliderContent.innerHTML = storeData.map(store => `
            <div class="store-card">
                <div class="card-inner">
                    <div class="card-image">
                        <img src="${store.image}" alt="${store.name}" onerror="this.src='https://placehold.co/300x220?text=No+Image'">
                    </div>
                    <div class="card-info">${store.name}</div>
                </div>
            </div>
        `).join('');

        // Logic cuộn slider
        const firstCard = document.querySelector('.store-card');
        const scrollStep = firstCard ? firstCard.clientWidth + 15 : 325;
        
        if(prevArrow) prevArrow.addEventListener('click', () => sliderContent.scrollLeft -= scrollStep);
        if(nextArrow) nextArrow.addEventListener('click', () => sliderContent.scrollLeft += scrollStep);
    }
});

/* SỰ KIỆN: KHỞI TẠO SLIDER KHI DOM LOAD */
document.addEventListener("DOMContentLoaded", function() {
    // Khai báo biến
    const sliderContent = document.getElementById("sliderContent");
    const prevArrow = document.getElementById("prevArrow");
    const nextArrow = document.getElementById("nextArrow");

    // Render dữ liệu ra HTML
    sliderContent.innerHTML = storeData.map(store => `
        <div class="store-card">
            <div class="card-inner">
                <div class="card-image"><img src="${store.image}" alt="${store.name}"></div>
                <div class="card-info">${store.name}</div>
            </div>
        </div>
    `).join('');

    // Logic cuộn slider
    const firstCard = document.querySelector('.store-card');
    const scrollStep = firstCard ? firstCard.clientWidth + 15 : 325;
    
    prevArrow.addEventListener('click', () => sliderContent.scrollLeft -= scrollStep);
    nextArrow.addEventListener('click', () => sliderContent.scrollLeft += scrollStep);
});