// DM_DesignCenter.js
document.addEventListener("DOMContentLoaded", () => {
  // banner appear
  const banner = document.querySelector(".banner-content");
  if (banner) {
    setTimeout(() => banner.classList.add("show"), 350);
  }


  // Reveal elements (intro, content blocks, overlay)
  const revealTargets = document.querySelectorAll(".intro-section, .content-block, .overlay-section");
  if (revealTargets.length) {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.18 });
    revealTargets.forEach(el => io.observe(el));
  }


  // Back to top button
  const btnTop = document.getElementById("backToTop");
  if (btnTop) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 600) btnTop.style.display = "block";
      else btnTop.style.display = "none";
    });
    btnTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
  }
});






