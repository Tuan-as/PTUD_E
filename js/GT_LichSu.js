// Hiệu ứng hiển thị khi cuộn trang
document.addEventListener("scroll", () => {
   const elements = document.querySelectorAll(".fade-in, .timeline-content, .overlay-text");
   elements.forEach((el) => {
     const rect = el.getBoundingClientRect();
     if (rect.top < window.innerHeight - 100) {
       el.classList.add("visible");
     }
   });
 });
 
