document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. HIỆU ỨNG SCROLL (Intersection Observer) ---
    const hiddenElements = document.querySelectorAll('.hidden');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                observer.unobserve(entry.target); // Chỉ chạy 1 lần
            }
        });
    }, { threshold: 0.1 });

    hiddenElements.forEach(el => observer.observe(el));


    // --- 2. XỬ LÝ POPUP ---
    const xemThemBtns = document.querySelectorAll('.xem-them');
    const closeBtns = document.querySelectorAll('.close');
    const popups = document.querySelectorAll('.popup');

    xemThemBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const popupId = btn.getAttribute('data-popup');
            const popup = document.getElementById(popupId);
            if(popup) popup.style.display = 'flex'; // Dùng flex để căn giữa
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.popup').style.display = 'none';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('popup')) {
            e.target.style.display = 'none';
        }
    });


    // --- 3. XỬ LÝ PHÂN TRANG (PAGINATION) ---
    // Logic: Ẩn hiện các div .img-item
    const itemsPerPage = 8; // Số ảnh mỗi trang (bạn để 4 hơi ít, tôi tăng lên 8 cho đẹp)

    document.querySelectorAll(".pagination-bar").forEach(bar => {
        const section = bar.closest('.award-section');
        const allItems = section.querySelectorAll(".award-images .img-item");
        
        if (allItems.length === 0) return;

        let currentPage = 1;
        const totalPages = Math.ceil(allItems.length / itemsPerPage);

        const prevBtn = bar.querySelector(".prev-btn");
        const nextBtn = bar.querySelector(".next-btn");
        const pageNumbersContainer = bar.querySelector(".page-numbers");

        function renderPagination() {
            pageNumbersContainer.innerHTML = "";

            // Giới hạn số nút trang hiển thị nếu quá nhiều trang (Optional logic)
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.textContent = i;
                btn.classList.add("page-btn");
                if (i === currentPage) btn.classList.add("active");

                btn.addEventListener("click", () => {
                    currentPage = i;
                    updateImages();
                    renderPagination();
                });
                pageNumbersContainer.appendChild(btn);
            }
        }

        function updateImages() {
            allItems.forEach((item, index) => {
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                if (index >= start && index < end) {
                    item.style.display = "block";
                    // Thêm class để CSS tạo hiệu ứng fade-in nếu muốn
                    item.classList.add("fade-in"); 
                } else {
                    item.style.display = "none";
                    item.classList.remove("fade-in");
                }
            });

            if(prevBtn) prevBtn.disabled = currentPage === 1;
            if(nextBtn) nextBtn.disabled = currentPage === totalPages;
        }

        if(prevBtn) {
            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateImages();
                    renderPagination();
                }
            });
        }

        if(nextBtn) {
            nextBtn.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateImages();
                    renderPagination();
                }
            });
        }

        // Khởi chạy lần đầu
        updateImages();
        renderPagination();
    });
});