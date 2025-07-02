document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function() {
            const btn = form.querySelector('input[type="submit"]');
            if (btn) {
                btn.value = "Đang xử lý...";
                btn.disabled = true;
                btn.style.opacity = "0.7";
            }
        });
    }
});
