document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll(
    ".banner-content, .intro-section, .content-block, .overlay-section"
  );


  elements.forEach(el => el.classList.remove("show"));


  const observer = new IntersectionObserver(
    (entries, observerInstance) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          observerInstance.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.2 }
  );


  elements.forEach(el => observer.observe(el));
});






