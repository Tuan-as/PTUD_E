document.addEventListener("DOMContentLoaded", () => {
 /* HÀM XỬ LÝ HIỆU ỨNG BANNER */
 // banner xuất hiện
 const banner = document.querySelector(".banner-content");
 if (banner) {
   setTimeout(() => {
     banner.classList.add("show");
   }, 400);
 }


 /* HÀM XỬ LÝ HIỆU ỨNG SCROLL CHO HERO SECTION */
 // hero section hiện ra khi cuộn tới
 const heroSection = document.querySelector(".hero-statements");
 if (heroSection) {
   heroSection.style.opacity = "0";
   heroSection.style.transform = "translateY(40px)";
   const heroObserver = new IntersectionObserver((entries) => {
     entries.forEach((entry) => {
       if (entry.isIntersecting) {
         // animate cho hero chạy lên và hiện rõ
         heroSection.animate(
           [
             { opacity: 0, transform: "translateY(40px)" },
             { opacity: 1, transform: "translateY(0)" }
           ],
           { duration: 1000, easing: "ease-out", fill: "forwards" }
         );
         // sau khi đã animate xong thì bỏ quan sát đi cho đỡ tốn tài nguyên
         heroObserver.unobserve(heroSection);
       }
     });
   }, { threshold: 0.3 }); // chỉ cần 30% phần tử xuất hiện là kích hoạt
   heroObserver.observe(heroSection);
 }


 /* HÀM XỬ LÝ HIỆU ỨNG CUỘN TRANG (REVEAL) */
 // các phần như card, box... xuất hiện khi cuộn tới
 const revealTargets = document.querySelectorAll(".card, .detail-box, .award-card, .banner-content, .stat");
 const io = new IntersectionObserver((entries, obs) => {
   entries.forEach(entry => {
     if(entry.isIntersecting){
       entry.target.classList.add("show"); // thêm class để kích hoạt CSS transition
       obs.unobserve(entry.target); // chỉ chạy 1 lần
     }
   });
 }, {threshold: 0.18});
 revealTargets.forEach(el => io.observe(el));


 /* HÀM XỬ LÝ POPUP MODAL (XEM THÊM) */
 const viewBtns = document.querySelectorAll(".view-more");
 const modals = document.querySelectorAll(".modal");

 viewBtns.forEach(btn => {
   btn.addEventListener("click", () => {
     const id = btn.getAttribute("data-target"); // lấy id modal tương ứng
     const modal = document.getElementById(id);
     if(modal){
       modal.style.display = "flex"; // hiện modal ra
       // hiệu ứng mờ dần (fade in)
       modal.animate(
         [
           { opacity: 0 },
           { opacity: 1 }
         ],
         { duration: 250, easing: "ease-out", fill: "forwards" }
       );
       // hiệu ứng phóng to nhẹ phần nội dung bên trong
       modal.querySelector(".modal-inner").animate(
         [
           { transform: "scale(0.95)", opacity: 0 },
           { transform: "scale(1)", opacity: 1 }
         ],
         { duration: 300, easing: "ease-out", fill: "forwards" }
       );
       modal.setAttribute("aria-hidden","false");
       document.body.style.overflow = "hidden"; // khóa scroll trang chính
     }
   });
 });


 /* HÀM ĐÓNG MODAL */
 // khi bấm ra ngoài hoặc nút close thì modal mờ dần rồi biến mất
 modals.forEach(modal => {
   modal.addEventListener("click", (e) => {
     if(e.target === modal || e.target.classList.contains("modal-close")){
       const fade = modal.animate(
         [
           { opacity: 1 },
           { opacity: 0 }
         ],
         { duration: 200, easing: "ease-in", fill: "forwards" }
       );
       fade.onfinish = () => {
         modal.setAttribute("aria-hidden","true");
         modal.style.display = "none";
         document.body.style.overflow = "";
       };
     }
   });
 });


 /* HÀM XỬ LÝ NÚT BACK TO TOP */
 // hiện nút khi kéo xuống khoảng 600px, bấm vào thì cuộn lên đầu
 const btnTop = document.getElementById("backToTop");
 window.addEventListener("scroll", () => {
   if(window.scrollY > 600) btnTop.style.display = "block";
   else btnTop.style.display = "none";
 });
 btnTop.addEventListener("click", () => window.scrollTo({top:0, behavior:"smooth"}));


 /* HÀM XỬ LÝ PHÍM TẮT (ESC) */
 // thêm cho tiện người dùng — bấm ESC là đóng popup
 document.addEventListener("keydown", (e) => {
   if(e.key === "Escape"){
     modals.forEach(m => {
       if(m.getAttribute("aria-hidden") === "false"){
         m.style.display = "none";
         m.setAttribute("aria-hidden","true");
         document.body.style.overflow = "";
       }
     });
   }
 });
});