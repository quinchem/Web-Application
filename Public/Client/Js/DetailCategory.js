document.addEventListener('DOMContentLoaded', () => {

    // Hover animation image
    const images = document.querySelectorAll('.related-image');

    images.forEach(img => {

        img.addEventListener('mouseenter', () => {
            img.style.transform = 'scale(1.02)';
            img.style.transition = '.3s ease';
        });

        img.addEventListener('mouseleave', () => {
            img.style.transform = 'scale(1)';
        });

    });

});