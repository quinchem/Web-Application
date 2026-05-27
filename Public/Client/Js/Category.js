/**
 * category.js
 * Đặt tại: Public/Client/Js/category.js
 * Dùng cho: Views/Client/Category/Detail.php
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Hover effect cho post titles (fallback cho trình duyệt cũ) ──
    document.querySelectorAll('.post-title-featured, .post-title-small').forEach(function (el) {
        var parent = el.closest('a');
        if (!parent) return;

        parent.addEventListener('mouseenter', function () {
            el.style.color = '#b90c17';
        });
        parent.addEventListener('mouseleave', function () {
            el.style.color = '';
        });
    });

    // ── Lazy load images (nếu trình duyệt hỗ trợ IntersectionObserver) ──
    if ('IntersectionObserver' in window) {
        var imgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imgObserver.unobserve(img);
                }
            });
        }, { rootMargin: '100px' });

        document.querySelectorAll('img[data-src]').forEach(function (img) {
            imgObserver.observe(img);
        });
    }

});