document.addEventListener("DOMContentLoaded", () => {
  const page1 = document.getElementById("trang1");
  const page2 = document.getElementById("trang2");
  const btnPrev = document.getElementById("prevPage");
  const btnNext = document.getElementById("nextPage");
  const pageNum = document.getElementById("pageNum");
  let currentPage = 1;


  // IntersectionObserver cho hiệu ứng hiện dần
  const rows = document.querySelectorAll(".thongtin-item");
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("hien-thi");
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  rows.forEach(row => observer.observe(row));


  // Chuyển trang
  const totalPages = 2;  // sửa nếu thêm trang
const pageNumbersContainer = document.getElementById("pageNumbers");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");


function renderPagination() {
  pageNumbersContainer.innerHTML = "";


  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.classList.add("page-btn");


    if (i === currentPage) btn.classList.add("active");


    btn.addEventListener("click", () => switchPage(i));
    pageNumbersContainer.appendChild(btn);
  }


  // Disable prev/next nếu cần
  prevBtn.classList.toggle("disabled", currentPage === 1);
  nextBtn.classList.toggle("disabled", currentPage === totalPages);
}


function switchPage(page) {
  currentPage = page;


  document.querySelectorAll("tbody[id^='trang']").forEach((tb, index) => {
    tb.className = (index + 1 === page) ? "trang-hien" : "trang-an";
  });


  renderPagination();
  window.scrollTo({ top: 0, behavior: "smooth" });
}


prevBtn.addEventListener("click", () => {
  if (currentPage > 1) switchPage(currentPage - 1);
});


nextBtn.addEventListener("click", () => {
  if (currentPage < totalPages) switchPage(currentPage + 1);
});


renderPagination();
});






