$(function () {

    /* ════════════════════════════════════════════════════════
       CẤU HÌNH & BIẾN TRẠNG THÁI
    ════════════════════════════════════════════════════════ */
    const POST_ID    = window.POST_DATA?.postId    ?? '';
    const IS_LOGGED  = window.POST_DATA?.isLoggedIn ?? false;

    let isLikeProcessing = false;
    let isSaveProcessing = false;


    /* ════════════════════════════════════════════════════════
       HELPER: POPUP YÊU CẦU ĐĂNG NHẬP
    ════════════════════════════════════════════════════════ */
    function requireLogin() {
        Swal.fire({
            title: '<div style="font-family:\'Barlow\',sans-serif;font-weight:700;color:#003049;font-size:1.8rem;">Yêu cầu đăng nhập</div>',
            html:  '<div style="font-family:\'Montserrat\',sans-serif;font-size:1rem;color:#5a7d9a;line-height:1.6;">Bạn cần đăng nhập để tương tác với bài viết.</div>',
            icon:           'info',
            iconColor:      '#B90C17',
            showCancelButton:  true,
            confirmButtonText: 'Đăng nhập ngay',
            cancelButtonText:  'Để sau',
            confirmButtonColor: '#003049',
            cancelButtonColor:  '#f8f9fa',
            backdrop: 'rgba(0,48,73,0.6)',
            customClass: {
                confirmButton: 'fw-bold px-4 py-2 rounded-pill',
                cancelButton:  'text-dark border fw-bold px-4 py-2 rounded-pill shadow-none'
            }
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=login';
            }
        });
    }


    /* ════════════════════════════════════════════════════════
       HELPER: TOAST THÔNG BÁO NHỎ
    ════════════════════════════════════════════════════════ */
    function showToast(html, bg = '#ffffff') {
        Swal.fire({
            toast:             true,
            position:          'top-end',
            showConfirmButton: false,
            timer:             1200,
            timerProgressBar:  true,
            html,
            background: bg,
            padding:    '12px 16px',
        });
    }

    function toastSaved() {
        showToast(`
            <div style="display:flex;align-items:center;gap:12px;">
                <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;"></i>
                <div>
                    <div style="font-weight:700;font-size:14px;color:#003049;">Đã lưu bài viết</div>
                </div>
            </div>`);
    }

    function toastUnsaved() {
        showToast(`
            <div style="display:flex;align-items:center;gap:12px;">
                <i class="fa-regular fa-bookmark" style="color:#003049;font-size:18px;"></i>
                <div>
                    <div style="font-weight:700;font-size:14px;color:#003049;">Đã bỏ lưu bài viết</div>
                    <div style="font-size:12px;color:#5a7d9a;margin-top:2px;">Bài viết đã được xoá khỏi danh sách</div>
                </div>
            </div>`);
    }

    function toastError() {
        showToast(`
            <div style="display:flex;align-items:center;gap:12px;">
                <i class="fa-solid fa-triangle-exclamation" style="color:#fff;font-size:18px;"></i>
                <div style="font-weight:700;font-size:14px;color:#fff;">Không thể kết nối máy chủ</div>
            </div>`, '#B90C17');
    }


    /* ════════════════════════════════════════════════════════
       NÚT THÍCH
    ════════════════════════════════════════════════════════ */
    $('#btn-like').on('click', async function () {
        if (!IS_LOGGED)         { requireLogin(); return; }
        if (isLikeProcessing)   return;

        isLikeProcessing = true;
        const $btn  = $(this);
        const $icon = $btn.find('i');
        const $text = $btn.find('span');

        $btn.prop('disabled', true).css('opacity', '0.6');

        try {
            const res  = await fetch('index.php?page=api_like', {
                method: 'POST',
                body:   new FormData(Object.assign(new FormData(), { post_id: POST_ID }))
            });

            // Dùng FormData đúng cách
            const fd = new FormData();
            fd.append('post_id', POST_ID);
            const resp = await fetch('index.php?page=api_like', { method: 'POST', body: fd });
            const data = await resp.json();

            if (data.status === 'unauthorized') { requireLogin(); return; }

            if (data.action === 'liked') {
                $btn.removeClass('btn-light text-muted').addClass('btn-liked');
                $icon.attr('class', 'fa-solid fa-thumbs-up me-2');
                $text.text('ĐÃ THÍCH');
            } else {
                $btn.removeClass('btn-liked').addClass('btn-light text-muted');
                $icon.attr('class', 'fa-regular fa-thumbs-up me-2');
                $text.text('THÍCH');
            }
        } catch {
            toastError();
        } finally {
            $btn.prop('disabled', false).css('opacity', '1');
            isLikeProcessing = false;
        }
    });


    /* ════════════════════════════════════════════════════════
       NÚT LƯU
    ════════════════════════════════════════════════════════ */
    $('#btn-save').on('click', async function () {
        if (!IS_LOGGED)        { requireLogin(); return; }
        if (isSaveProcessing)  return;

        isSaveProcessing = true;
        const $btn  = $(this);
        const $icon = $btn.find('i');
        const $text = $btn.find('span');

        $btn.prop('disabled', true).css('opacity', '0.6');

        try {
            const fd = new FormData();
            fd.append('post_id', POST_ID);
            const resp = await fetch('index.php?page=api_save', { method: 'POST', body: fd });
            const data = await resp.json();

            if (data.status === 'unauthorized') { requireLogin(); return; }

            if (data.action === 'saved') {
                $btn.removeClass('btn-light text-muted').addClass('btn-saved');
                $icon.attr('class', 'fa-solid fa-bookmark me-2');
                $text.text('ĐÃ LƯU');
                toastSaved();
            } else {
                $btn.removeClass('btn-saved').addClass('btn-light text-muted');
                $icon.attr('class', 'fa-regular fa-bookmark me-2');
                $text.text('LƯU');
                toastUnsaved();
            }
        } catch {
            toastError();
        } finally {
            $btn.prop('disabled', false).css('opacity', '1');
            isSaveProcessing = false;
        }
    });


    /* ════════════════════════════════════════════════════════
       GỬI BÌNH LUẬN
    ════════════════════════════════════════════════════════ */
    $('#btn-submit-comment').on('click', function () {
        if (!IS_LOGGED) { requireLogin(); return; }

        const $textarea = $('#comment-content');
        const content   = $textarea.val().trim();

        if (!content) {
            Swal.fire('Cảnh báo', 'Vui lòng nhập nội dung bình luận trước khi gửi!', 'warning');
            return;
        }

        const fd = new FormData();
        fd.append('post_id', POST_ID);
        fd.append('content', content);

        fetch('index.php?page=api_comment', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'unauthorized') { requireLogin(); return; }

                if (data.status === 'success') {
                    Swal.fire({
                        icon:               'success',
                        title:              'Thành công',
                        text:               'Bình luận của bạn đã được đăng tải!',
                        timer:              1500,
                        showConfirmButton:  false,
                    });

                    // Xoá ô nhập
                    $textarea.val('');

                    // Xoá thông báo "Chưa có bình luận" nếu tồn tại
                    $('#comment-area p.text-muted').remove();

                    // Tạo HTML bình luận mới và chèn vào đầu danh sách
                    const newHtml = `
                        <div class="d-flex mb-4 pb-4 border-bottom" style="animation:fadeIn .5s;">
                            <img src="${data.comment.avatar}"
                                 class="comment-avatar me-3" alt="Avatar">
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold comment-username">${data.comment.full_name}</div>
                                    <div class="small text-muted">${data.comment.created_at}</div>
                                </div>
                                <div class="comment-body">${data.comment.content}</div>
                                <a href="#" class="comment-reply-btn">TRẢ LỜI</a>
                            </div>
                        </div>`;
                    $('#comment-area').prepend(newHtml);

                    // Tăng bộ đếm bình luận
                    const $title = $('#comment-count-title');
                    const count  = parseInt($title.data('count') || 0) + 1;
                    $title.data('count', count).text(`Bình luận (${count})`);

                } else {
                    Swal.fire('Lỗi', data.message, 'error');
                }
            })
            .catch(() => toastError());
    });


    /* ════════════════════════════════════════════════════════
       PHÂN TRANG BÌNH LUẬN (event delegation — nút render sau AJAX)
    ════════════════════════════════════════════════════════ */
    $(document).on('click', '#comment-pagination-wrapper .btn-pagination-arrow', function () {
        const page = $(this).data('page');
        if (page) loadComments(page);
    });


    /* ════════════════════════════════════════════════════════
       HÀM TẢI BÌNH LUẬN THEO TRANG (AJAX)
    ════════════════════════════════════════════════════════ */
    window.loadComments = function (page) {
        $.get('index.php', {
            page:    'api_get_comments',
            post_id: POST_ID,
            cpage:   page,
        })
        .done(data => {
            // Cập nhật danh sách & phân trang
            $('#comment-area').html(data.html);
            $('#comment-pagination-wrapper').html(data.pagination);

            // Cập nhật bộ đếm
            const $title = $('#comment-count-title');
            $title.data('count', data.total).text(`Bình luận (${data.total})`);

            // Scroll lên khu vực bình luận
            $('html, body').animate({
                scrollTop: $('#comment-section').offset().top - 80
            }, 400);
        })
        .fail(() => toastError());
    };

});  // end $(function)