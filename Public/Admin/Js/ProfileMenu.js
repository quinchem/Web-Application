function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    menu.classList.toggle('active');
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('profileWrapper');
    const menu = document.getElementById('profileMenu');
    if (wrapper && !wrapper.contains(e.target)) {
        menu.classList.remove('active');
    }
});