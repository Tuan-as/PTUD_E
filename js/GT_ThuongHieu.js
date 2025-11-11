// Hiệu ứng cuộn hiện dần và biến mất
document.addEventListener("DOMContentLoaded", () => {
   const fadeSections = document.querySelectorAll(".fade-section");
   const cards = document.querySelectorAll(".value-card");
    const observer = new IntersectionObserver(entries => {
     entries.forEach(entry => {
       if (entry.isIntersecting) {
         entry.target.classList.add("show");
       } else {
         entry.target.classList.remove("show");
       }
     });
   }, { threshold: 0.2 });
    fadeSections.forEach(sec => observer.observe(sec));
   cards.forEach(card => observer.observe(card));
 });
 
