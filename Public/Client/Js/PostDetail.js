function requireLogin() {
    if (typeof bootstrap !== 'undefined') {
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    } else {
        window.location.href = 'index.php?page=login';
    }
}

function handleLike() {
    if (!isLoggedIn) { requireLogin(); return; }

    let formData = new FormData();
    formData.append('post_id', currentPostId);
    fetch('index.php?page=api_like', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'unauthorized') { requireLogin(); return; }

        const btn = document.getElementById('btn-like');
        if(data.status === 'liked') {
            btn.style.background = '#003049'; btn.style.color = '#fff';
            btn.classList.remove('btn-light', 'text-muted');
            btn.querySelector('span').innerText = 'ĐÃ THÍCH';
            Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        } else {
            btn.style.background = ''; btn.style.color = '';
            btn.classList.add('btn-light', 'text-muted');
            btn.querySelector('span').innerText = 'THÍCH';
            Swal.fire({ icon: 'info', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        }
    });
}

function handleSave() {
    if (!isLoggedIn) { requireLogin(); return; }

    let formData = new FormData();
    formData.append('post_id', currentPostId);
    fetch('index.php?page=api_save', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'unauthorized') { requireLogin(); return; }

        const btn = document.getElementById('btn-save');
        if(data.status === 'saved') {
            btn.style.background = '#B90C17'; btn.style.color = '#fff';
            btn.classList.remove('btn-light', 'text-muted');
            btn.querySelector('span').innerText = 'ĐÃ LƯU';
            Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        } else {
            btn.style.background = ''; btn.style.color = '';
            btn.classList.add('btn-light', 'text-muted');
            btn.querySelector('span').innerText = 'LƯU';
            Swal.fire({ icon: 'info', title: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        }
    });
}

function submitComment() {
    if (!isLoggedIn) { requireLogin(); return; }

    const contentInput = document.getElementById('comment-content');
    const content = contentInput.value.trim();
    
    if(!content) {
        Swal.fire('Cảnh báo', 'Vui lòng nhập nội dung bình luận trước khi bấm gửi!', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('post_id', currentPostId);
    formData.append('content', content);
    
    fetch('index.php?page=api_comment', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'unauthorized') { requireLogin(); return; }

        if(data.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Thành công', text: 'Bình luận của bạn đã được đăng tải!', timer: 1500, showConfirmButton: false });
            
            contentInput.value = '';
            const commentArea = document.querySelector('.comment-area');
            const noCommentMsg = commentArea.querySelector('p.text-muted');
            if (noCommentMsg) noCommentMsg.remove();

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
            
            commentArea.insertAdjacentHTML('afterbegin', newCommentHtml);

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