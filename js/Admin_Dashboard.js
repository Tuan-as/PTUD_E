document.addEventListener("DOMContentLoaded", function () {

  /* ===== Hiệu ứng xuất hiện ===== */
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });

  /* ===== Chart ===== */
  if (typeof revenueLabels !== "undefined") {
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Doanh thu (đ)',
          data: revenueData,
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        }
      }
    });
  }
});
