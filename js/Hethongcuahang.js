/* DỮ LIỆU CỬA HÀNG */
const stores = [
    { name: "Vincom Mega Mall Smart City", region: "hn", address: "Nam Từ Liêm, Hà Nội", hours: "10:00 - 21:30", phone: "024-3202-2208", map: "Vincom Mega Mall Smart City" },
    { name: "Vincom Center Metropolis", region: "hn", address: "Liễu Giai, Ba Đình, Hà Nội", hours: "9:00 - 22:00", phone: "024-3974-9999", map: "Vincom Center Metropolis" },
    { name: "Vincom Mega Mall Royal City", region: "hn", address: "72A Nguyễn Trãi, Thanh Xuân, Hà Nội", hours: "9:00 - 22:00", phone: "024-6664-8888", map: "Vincom Mega Mall Royal City" },
    { name: "Vincom Center Đồng Khởi", region: "hcm", address: "72 Lê Thánh Tôn, Quận 1, TP.HCM", hours: "9:30 - 22:00", phone: "028-3936-9999", map: "Vincom Center Đồng Khởi" },
    { name: "Vincom Mega Mall Landmark 81", region: "hcm", address: "Vinhomes Central Park, Bình Thạnh, TP.HCM", hours: "10:00 - 22:00", phone: "028-3521-8181", map: "Vincom Landmark 81" },
    { name: "Vincom Plaza Quận 9", region: "hcm", address: "504 Lê Văn Việt, Quận 9, TP.HCM", hours: "9:30 - 22:00", phone: "028-3720-9090", map: "Vincom Plaza Quận 9" },
    { name: "Vincom Plaza Hải Phòng", region: "bac", address: "Lạch Tray, Hải Phòng", hours: "9:00 - 22:00", phone: "0225-355-8888", map: "Vincom Hải Phòng" },
    { name: "Vincom Plaza Hạ Long", region: "bac", address: "Bạch Đằng, Hạ Long, Quảng Ninh", hours: "9:00 - 21:30", phone: "0203-383-9999", map: "Vincom Hạ Long" },
    { name: "Vincom Plaza Bắc Ninh", region: "bac", address: "Trần Hưng Đạo, Bắc Ninh", hours: "9:00 - 22:00", phone: "0222-389-7777", map: "Vincom Bắc Ninh" },
    { name: "Vincom Plaza Đà Nẵng", region: "trung", address: "910A Ngô Quyền, Đà Nẵng", hours: "9:00 - 22:00", phone: "0236-358-8888", map: "Vincom Đà Nẵng" },
    { name: "Vincom Plaza Huế", region: "trung", address: "50A Hùng Vương, TP Huế", hours: "9:00 - 22:00", phone: "0234-393-9999", map: "Vincom Huế" },
    { name: "Vincom Plaza Quy Nhơn", region: "trung", address: "Lê Duẩn, Quy Nhơn", hours: "9:00 - 21:30", phone: "0256-383-8888", map: "Vincom Quy Nhơn" },
    { name: "Vincom Plaza Cần Thơ", region: "nam", address: "Hùng Vương, Ninh Kiều, Cần Thơ", hours: "9:00 - 22:00", phone: "0292-389-9999", map: "Vincom Cần Thơ" },
    { name: "Vincom Plaza Bà Rịa", region: "nam", address: "Cách Mạng Tháng 8, Bà Rịa", hours: "9:00 - 22:00", phone: "0254-373-8888", map: "Vincom Bà Rịa" },
    { name: "Vincom Plaza Sóc Trăng", region: "nam", address: "Lê Duẩn, Sóc Trăng", hours: "9:00 - 21:30", phone: "0299-382-7777", map: "Vincom Sóc Trăng" }
];

/* KHAI BÁO BIẾN LIÊN KẾT TỚI PHẦN TỬ HTML */
const storeList = document.getElementById("storeList");
const mapFrame = document.getElementById("mapFrame");
const storeInfo = document.getElementById("storeInfo");
const searchInput = document.getElementById("searchInput");
const filterBtns = document.querySelectorAll(".filter-btn");

/* HÀM HIỂN THỊ DANH SÁCH CỬA HÀNG (THEO KHU VỰC + TÌM KIẾM) */
function renderStores(region = "all", search = "") {
    storeList.innerHTML = "";
    const filtered = stores.filter(s =>
        (region === "all" || s.region === region) &&
        s.name.toLowerCase().includes(search.toLowerCase())
    );

    filtered.forEach(s => {
        const li = document.createElement("li");
        li.textContent = s.name;
        li.onclick = () => selectStore(s);
        storeList.appendChild(li);
    });
}

/* HÀM CẬP NHẬT THÔNG TIN + BẢN ĐỒ KHI CHỌN CỬA HÀNG */
function selectStore(s) {
    // Lưu ý: Cập nhật URL bản đồ Google Maps Embed chuẩn
    mapFrame.src = `https://maps.google.com/maps?q=${encodeURIComponent(s.map)}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
    
    storeInfo.innerHTML = `
        <h3>${s.name}</h3>
        <p><strong>Địa chỉ:</strong> ${s.address}</p>
        <p><strong>Giờ mở cửa:</strong> ${s.hours}</p>
        <p><strong>Điện thoại:</strong> ${s.phone}</p>
    `;
}

/* SỰ KIỆN: CHỌN NÚT LỌC KHU VỰC */
filterBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        filterBtns.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        renderStores(btn.dataset.region, searchInput.value);
    });
});

/* SỰ KIỆN: TÌM KIẾM THEO TÊN CỬA HÀNG */
searchInput.addEventListener("input", () => {
    const activeRegion = document.querySelector(".filter-btn.active").dataset.region;
    renderStores(activeRegion, searchInput.value);
});

/* KHỞI TẠO TRANG LẦN ĐẦU */
renderStores();