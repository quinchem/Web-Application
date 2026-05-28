document.addEventListener('DOMContentLoaded', function () {

    // Hover title effect
    document.querySelectorAll('.featured-title, .side-title').forEach(function (title) {

        const parent = title.closest('a');

        if (!parent) return;

        parent.addEventListener('mouseenter', function () {
            title.style.color = '#b90c17';
        });

        parent.addEventListener('mouseleave', function () {
            title.style.color = '';
        });

    });

    // Smooth image hover
    document.querySelectorAll('.featured-image').forEach(function (img) {

        img.addEventListener('mouseenter', function () {
            img.style.transform = 'scale(1.01)';
        });

        img.addEventListener('mouseleave', function () {
            img.style.transform = 'scale(1)';
        });

    });

});