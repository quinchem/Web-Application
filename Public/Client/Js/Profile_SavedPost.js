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

            if (
                data.status === 'removed' ||
                data.status === 'unsaved' ||
                data.status === 'success'
            ) {
                const item = button.closest('.saved-post-item');

                if (item) {
                    item.remove();
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã bỏ lưu bài viết.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1200
                    });
                }
            }
        })
        .catch(function (error) {
            console.error(error);
        });
}