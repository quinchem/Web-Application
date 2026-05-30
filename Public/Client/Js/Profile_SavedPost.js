document.addEventListener('click', function (e) {
    const pageBtn = e.target.closest('.js-saved-page');

    if (pageBtn) {
        e.preventDefault();

        const page = pageBtn.dataset.page;
        loadSavedPostsPage(page);
        return;
    }

    const removeBtn = e.target.closest('.js-remove-saved');

    if (removeBtn) {
        e.preventDefault();

        const postId = removeBtn.dataset.postId;
        removeSavedPost(postId, removeBtn);
    }
});

function loadSavedPostsPage(page) {
    const contentBox = document.querySelector('#dynamic-content');

    if (!contentBox) {
        console.error('Không tìm thấy #dynamic-content');
        return;
    }

    contentBox.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `;

    fetch('index.php?page=client_saved_posts_page&saved_page=' + page)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Không tải được trang đã lưu');
            }

            return response.text();
        })
        .then(function (html) {
            contentBox.innerHTML = html;

            window.history.pushState(
                { tab: 'saved', saved_page: page },
                '',
                'index.php?page=client_profile&tab=saved&saved_page=' + page
            );
        })
        .catch(function (error) {
            console.error(error);
            contentBox.innerHTML = `
                <div class="alert alert-danger">
                    Không thể tải danh sách bài viết đã lưu.
                </div>
            `;
        });
}

function removeSavedPost(postId, button) {
    if (!postId) return;

    const formData = new FormData();
    formData.append('post_id', postId);

    fetch('index.php?page=api_save', {
        method: 'POST',
        body: formData
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.status === 'unauthorized') {
                window.location.href = 'index.php?page=login';
                return;
            }

            if (data.action === 'unsaved') {
                const item = button.closest('.saved-post-item');
                if (item) {
                    item.style.transition = 'opacity 0.3s';
                    item.style.opacity = '0';
                    setTimeout(() => item.remove(), 300);
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1200,
                        timerProgressBar: true,
                        html: `
                            <div style="display:flex;align-items:center;gap:12px;">
                                <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;flex-shrink:0;"></i>
                                <div>
                                    <div style="font-weight:700;font-size:14px;color:#003049;">Đã bỏ lưu bài viết</div>
                                    <div style="font-size:12px;color:#5a7d9a;margin-top:2px;">Bài viết đã được xoá khỏi danh sách</div>
                                </div>
                            </div>`,
                        background: '#fff',
                        padding: '12px 16px'
                    });
                }
            }
        })
        .catch(function (error) {
            console.error(error);
        });
}