document.addEventListener('DOMContentLoaded', function () {
    const postTitles = document.querySelectorAll('.post-title');

    postTitles.forEach(function (title) {
        title.addEventListener('mouseenter', function () {
            title.classList.add('is-hover');
        });

        title.addEventListener('mouseleave', function () {
            title.classList.remove('is-hover');
        });
    });
    
// Khởi tạo carousel nếu phần tử tồn tại
    const heroCarousel = document.querySelector('#heroCarousel');

    if (heroCarousel && typeof bootstrap !== 'undefined') {
        new bootstrap.Carousel(heroCarousel, {
            interval: 4000,
            ride: 'carousel',
            pause: 'hover',
            wrap: true
        });
    }
});
