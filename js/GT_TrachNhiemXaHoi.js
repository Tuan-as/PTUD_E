// Khi trang load xong
document.addEventListener("DOMContentLoaded", () => {
   const elements = document.querySelectorAll(
     ".banner-content, .intro-section, .link-card"
   );
 
   // Ẩn ban đầu
   elements.forEach(el => {
     el.classList.remove("show");
   });
 
   // Tạo observer để phát hiện khi phần tử vào vùng nhìn thấy
   const observer = new IntersectionObserver(
     (entries) => {
       entries.forEach((entry) => {
         if (entry.isIntersecting) {
           entry.target.classList.add("show");
           // Nếu m chỉ muốn hiện 1 lần thì bỏ theo dõi sau khi hiện
           observer.unobserve(entry.target);
         }
       });
     },
     {
       threshold: 0.2, // 20% phần tử xuất hiện thì kích hoạt
     }
   );
 
   // Áp dụng observer cho tất cả phần tử
   elements.forEach((el) => observer.observe(el));
 });
 
 
 
 
