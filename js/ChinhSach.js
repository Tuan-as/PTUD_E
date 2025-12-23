// FILE: js/ChinhSach.js
document.addEventListener("DOMContentLoaded", function() {
    var acc = document.getElementsByClassName("accordion-button");
    
    // Mở mục đầu tiên mặc định (Tùy chọn)
    if (acc.length > 0) {
        acc[0].classList.add("active");
        var firstPanel = acc[0].nextElementSibling;
        firstPanel.style.maxHeight = firstPanel.scrollHeight + "px";
    }

    for (var i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
});