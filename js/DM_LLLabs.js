document.addEventListener("DOMContentLoaded", () => {
  const intro = document.querySelector(".intro-section");
  const blocks = document.querySelectorAll(".content-block");


  if (intro) {
    setTimeout(() => intro.classList.add("show"), 300);
  }


  blocks.forEach((block, i) => {
    setTimeout(() => block.classList.add("show"), 600 + i * 300);
  });
});




