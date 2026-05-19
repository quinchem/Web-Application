function goToPage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', 'admin_user_posts');
    url.searchParams.set('p', page);
    window.location.href = url.toString();
}