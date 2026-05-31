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
    if (button.dataset.processing === 'true') return;

    button.dataset.processing = 'true';
    button.disabled = true;
    button.style.opacity = '0.6';

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

<<<<<<< HEAD
            if (data.action === 'unsaved') {
=======
            if (
                data.action === 'unsaved' ||
                data.status === 'removed' ||
                data.status === 'unsaved' ||
                data.status === 'success'
            ) {
                const icon = button.querySelector('i');

                if (icon) {
                    icon.className = 'fa-regular fa-bookmark';
                }

>>>>>>> 03f4c4261d4574d28b5281e7cd0aa8481b0405fb
                const item = button.closest('.saved-post-item');
                if (item) {
<<<<<<< HEAD
                    item.style.transition = 'opacity 0.3s';
                    item.style.opacity = '0';
                    setTimeout(() => item.remove(), 300);
=======
                    item.style.transition = '0.25s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(10px)';

                    setTimeout(function () {
                        item.remove();

                        const remainingItems = document.querySelectorAll('.saved-post-item');

                        if (remainingItems.length === 0) {
                            const listBox = document.querySelector('.saved-post-list');

                            if (listBox) {
                                listBox.innerHTML = `
                                    <div class="saved-empty">
                                        <p class="mb-0">Bạn chưa lưu bài viết nào.</p>
                                    </div>
                                `;
                            }
                        }
                    }, 250);
>>>>>>> 03f4c4261d4574d28b5281e7cd0aa8481b0405fb
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
<<<<<<< HEAD
                        timer: 1200,
=======
                        timer: 1000,
>>>>>>> 03f4c4261d4574d28b5281e7cd0aa8481b0405fb
                        timerProgressBar: true,
                        html: `
                            <div style="display:flex;align-items:center;gap:12px;">
                                <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;flex-shrink:0;"></i>
                                <div>
<<<<<<< HEAD
                                    <div style="font-weight:700;font-size:14px;color:#003049;">Đã bỏ lưu bài viết</div>
                                    <div style="font-size:12px;color:#5a7d9a;margin-top:2px;">Bài viết đã được xoá khỏi danh sách</div>
                                </div>
                            </div>`,
                        background: '#fff',
                        padding: '12px 16px'
=======
                                    <div style="font-weight:700;font-size:14px;color:#003049;letter-spacing:0.2px;">Đã bỏ lưu bài viết</div>
                                </div>
                            </div>`,
                        background: '#fff',
                        padding: '12px 16px',
                        customClass: {
                            popup: 'swal-unsave-toast'
                        },
                        showClass: {
                            popup: 'swal-slide-in'
                        },
                        hideClass: {
                            popup: 'swal-slide-out'
                        }
>>>>>>> 03f4c4261d4574d28b5281e7cd0aa8481b0405fb
                    });
                }
            }
        })
        .catch(function (error) {
            console.error(error);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    html: `
                        <div style="display:flex;align-items:center;gap:12px;">
                            <i class="fa-solid fa-triangle-exclamation" style="color:#fff;font-size:18px;flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:700;font-size:14px;color:#fff;">Không thể kết nối</div>
                                <div style="font-size:12px;color:rgba(255,255,255,0.75);margin-top:2px;">Vui lòng kiểm tra mạng và thử lại</div>
                            </div>
                        </div>`,
                    background: '#B90C17',
                    padding: '12px 16px',
                    customClass: {
                        popup: 'swal-error-toast'
                    }
                });
            }
        })
        .finally(function () {
            button.disabled = false;
            button.style.opacity = '1';
            button.dataset.processing = 'false';
        });
}
