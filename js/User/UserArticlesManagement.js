document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('manageTableBody');
    const searchInput = document.getElementById('articleSearch');

    // Mở trang đăng bài mới (giả lập)
    document.querySelector('.btn-action-red').addEventListener('click', () => {
        alert("Chức năng đang được xây dựng!");
    });

    // Tìm kiếm trong bảng
    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const title = row.querySelector('.article-name-heading').innerText.toLowerCase();
            row.style.display = title.includes(filter) ? '' : 'none';
        });
    });

    // Xóa bài viết
    tableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-del-article')) {
            const row = e.target.closest('tr');
            if (confirm('Thie có chắc chắn muốn xóa bài viết này không?')) {
                row.classList.add('fade-out'); // Thêm hiệu ứng nếu có
                setTimeout(() => row.remove(), 300);
            }
        }
    });
});