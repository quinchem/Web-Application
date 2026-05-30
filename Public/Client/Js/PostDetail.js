const postDetailConfig = window.POST_DETAIL_CONFIG || {};
const currentPostId = postDetailConfig.currentPostId || '';
const isLoggedIn = Boolean(postDetailConfig.isLoggedIn);

let isSaveProcessing = false;
let isLikeProcessing = false;

    function requireLogin() {
        Swal.fire({
            title: '<div style="font-family: \'Barlow\', sans-serif; font-weight: 700; color: #003049; font-size: 1.8rem;">Yêu cầu đăng nhập</div>',
            html: '<div style="font-family: \'Montserrat\', sans-serif; font-size: 1rem; color: #5a7d9a; line-height: 1.6;">Bạn cần đăng nhập tài khoản để tương tác với bài viết</div>',
            icon: 'info',
            iconColor: '#B90C17',
            showCancelButton: true,
            confirmButtonText: 'Đăng nhập ngay',
            cancelButtonText: 'Để sau',
            confirmButtonColor: '#003049',
            cancelButtonColor: '#f8f9fa',
            backdrop: `rgba(0, 48, 73, 0.6)`,
            customClass: {
                confirmButton: 'fw-bold px-4 py-2 rounded-pill',
                cancelButton: 'text-dark border fw-bold px-4 py-2 rounded-pill shadow-none'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=login';
            }
        });
    }

    async function handleLike() {
        if (!isLoggedIn) {
            requireLogin();
            return;
        }
        if (isLikeProcessing) return;

        isLikeProcessing = true;
        const btn = document.getElementById('btn-like');
        btn.disabled = true;
        btn.style.opacity = '0.6';

        try {
            const formData = new FormData();
            formData.append('post_id', currentPostId);

            const res = await fetch('index.php?page=api_like', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.status === 'unauthorized') {
                requireLogin();
                return;
            }

            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');

            if (data.action === 'liked') {
                btn.classList.add('active-like');
                btn.classList.remove('btn-light', 'text-muted');
                btn.style.cssText = 'transition:0.3s;background:#003049;color:#fff;';
                icon.className = 'fa-solid fa-thumbs-up me-2';
                text.innerText = 'ĐÃ THÍCH';
            } else if (data.action === 'unliked') {
                btn.classList.remove('active-like');
                btn.classList.add('btn-light', 'text-muted');
                btn.style.cssText = 'transition:0.3s;';
                icon.className = 'fa-regular fa-thumbs-up me-2';
                text.innerText = 'THÍCH';
            }
        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Không thể kết nối máy chủ',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
            isLikeProcessing = false;
        }
    }

    async function handleSave() {
        if (!isLoggedIn) {
            requireLogin();
            return;
        }
        if (isSaveProcessing) return;

        isSaveProcessing = true;
        const btn = document.getElementById('btn-save');
        btn.disabled = true;
        btn.style.opacity = '0.6';

        try {
            const response = await fetch('index.php?page=api_save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    post_id: currentPostId
                })
            });
            const data = await response.json();

            if (data.status === 'unauthorized') {
                requireLogin();
                return;
            }

            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');

            if (data.action === 'saved') {
                btn.classList.add('active-save');
                btn.classList.remove('btn-light', 'text-muted');
                btn.style.cssText = 'transition:0.3s;background:#B90C17;color:#fff;';
                icon.className = 'fa-solid fa-bookmark me-2';
                text.innerText = 'ĐÃ LƯU';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    html: `
                     <div style="display:flex;align-items:center;gap:12px;">
                        <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;flex-shrink:0;"></i>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#003049;letter-spacing:0.2px;">Đã lưu bài viết</div>
                        </div>
                    </div>`,
                    background: '#ffffff',
                    padding: '12px 16px',
                    customClass: {
                        popup: 'swal-save-toast'
                    },
                    showClass: {
                        popup: 'swal-slide-in'
                    },
                    hideClass: {
                        popup: 'swal-slide-out'
                    }
                });
            } else if (data.action === 'unsaved') {
                btn.classList.remove('active-save');
                btn.classList.add('btn-light', 'text-muted');
                btn.style.cssText = 'transition:0.3s;';
                icon.className = 'fa-regular fa-bookmark me-2';
                text.innerText = 'LƯU';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    html: `
                    <div style="display:flex;align-items:center;gap:12px;">
                        <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;flex-shrink:0;"></i>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#003049;letter-spacing:0.2px;">Đã bỏ lưu bài viết</div>
                            <div style="font-size:12px;color:#5a7d9a;margin-top:2px;">Bài viết đã được xoá khỏi danh sách</div>
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
                });
            }
        } catch (error) {
            console.error(error);
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
        } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
            isSaveProcessing = false;
        }
    }

    function submitComment() {
        if (!isLoggedIn) {
            requireLogin();
            return;
        }

        const contentInput = document.getElementById('comment-content');
        const content = contentInput.value.trim();

        if (!content) {
            Swal.fire('Cảnh báo', 'Vui lòng nhập nội dung bình luận trước khi bấm gửi!', 'warning');
            return;
        }

        let formData = new FormData();
        formData.append('post_id', currentPostId);
        formData.append('content', content);

        fetch('index.php?page=api_comment', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'unauthorized') {
                    requireLogin();
                    return;
                }

                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Bình luận của bạn đã được đăng tải!',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // 1. Dọn dẹp ô nhập liệu
                    contentInput.value = '';

                    // 2. Chèn bình luận mới vào đầu danh sách (AJAX thuần không reload)
                    const commentArea = document.querySelector('.comment-area');

                    // Xóa dòng chữ "Chưa có bình luận nào" nếu có
                    const noCommentMsg = commentArea.querySelector('p.text-muted');
                    if (noCommentMsg) noCommentMsg.remove();

                    // Tạo cấu trúc HTML cho bình luận mới
                    const newCommentHtml = `
                    <div class="d-flex mb-4 pb-4 border-bottom" style="animation: fadeIn 0.5s;">
                        <img src="${data.comment.avatar}" class="comment-avatar me-3" alt="Avatar">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-bold" style="color: var(--navy);">${data.comment.full_name}</div>
                                <div class="small text-muted">${data.comment.created_at}</div>
                            </div>
                            <div class="text-dark" style="font-size: 0.95rem; line-height: 1.6;">${data.comment.content}</div>
                            <a href="#" class="text-danger fw-bold text-decoration-none mt-2 d-inline-block" style="font-size: 0.8rem; color: var(--red) !important;">TRẢ LỜI</a>
                        </div>
                    </div>
                `;

                    // Chèn vào vị trí trên cùng
                    commentArea.insertAdjacentHTML('afterbegin', newCommentHtml);

                    // 3. Tăng bộ đếm bình luận lên 1
                    const titleEl = document.getElementById('comment-count-title');
                    let currentCount = parseInt(titleEl.getAttribute('data-count'));
                    currentCount++;
                    titleEl.setAttribute('data-count', currentCount);
                    titleEl.innerText = `Bình luận (${currentCount})`;

                } else {
                    Swal.fire('Lỗi', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Lỗi AJAX:', error);
                Swal.fire('Lỗi', 'Đã có lỗi xảy ra trong quá trình xử lý.', 'error');
            });
    }

    function loadComments(page) {
        fetch(`index.php?page=api_get_comments&post_id=${currentPostId}&cpage=${page}`)
            .then(res => res.json())
            .then(data => {
                // Cập nhật danh sách comment
                document.querySelector('.comment-area').innerHTML = data.html;

                // Cập nhật pagination
                document.querySelector('#comment-pagination-wrapper').innerHTML = data.pagination;

                // Cập nhật số lượng comment
                const titleEl = document.getElementById('comment-count-title');
                titleEl.setAttribute('data-count', data.total);
                titleEl.innerText = `Bình luận (${data.total})`;

                // Scroll lên đầu phần comment
                document.getElementById('comment-section').scrollIntoView({
                    behavior: 'smooth'
                });
            })
            .catch(err => {
                console.error('Lỗi loadComments:', err);
            });
    }