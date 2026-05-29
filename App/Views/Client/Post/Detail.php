<?php
require_once __DIR__ . '/../../Partials/Client/Header.php';
$defaultAvatar = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

$usernameSession = $_SESSION['user_name'] ?? '';
$avatar = $_SESSION['avatar'] ?? $defaultAvatar;

$isSaved = $isSaved ?? false;
$isLiked = $isLiked ?? false;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&family=Montserrat:ital,wght@0,400;0,500;0,700;1,400&family=Newsreader:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="Public/Client/Css/PostDetail.css">


<main id="page-article" class="container my-5">
    <div class="row">
        <div class="col-lg-8 pe-lg-5">

            <div class="breadcrumb text-muted mb-3 text-uppercase fw-bold" style="font-size: 0.8rem;">
                <a href="index.php?page=homepage" class="text-decoration-none text-muted">Trang chủ</a>

                <?php if (!empty($post['parent_category_name'])): ?>
                    <span class="mx-2">></span>
                    <a href="index.php?page=category&name=<?= urlencode($post['parent_category_name']) ?>" class="text-decoration-none text-muted">
                        <?= htmlspecialchars($post['parent_category_name']) ?>
                    </a>
                <?php endif; ?>

                <span class="mx-2">></span>
                <a href="index.php?page=category&id=<?= $post['category_id'] ?>" class="text-decoration-none text-danger" style="color: var(--red) !important;">
                    <?= htmlspecialchars($post['category_name']) ?>
                </a>
            </div>

            <h1 class="article-title fw-bold"><?= htmlspecialchars($post['title']) ?></h1>

            <div class="d-flex align-items-center mb-4 pb-3 border-bottom mt-4">
                <img src="<?= htmlspecialchars($post['avatar'] ?? $defaultAvatar) ?>" alt="Avatar" class="rounded-circle me-3" style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #eee;">

                <div class="w-100 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-6" style="color: var(--navy);">
                            <?= htmlspecialchars($post['author_name']) ?>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.85rem;">
                            <i class="fa-regular fa-calendar me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($post['published_at'])) ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <?php if (!empty($post['parent_category_name'])): ?>
                            <span class="badge border text-muted bg-light me-1 px-3 py-2 rounded-pill fw-bold"
                                style="font-size: 0.75rem;">
                                <?= htmlspecialchars($post['parent_category_name']) ?>
                            </span>
                        <?php endif; ?>

                        <span class="badge px-3 py-2 rounded-pill shadow-sm" style="background-color: var(--red); font-size: 0.8rem; letter-spacing: 0.5px;">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                    </div>
                </div>

            </div>

            <div class="sapo-box mb-4">
                <?= nl2br(htmlspecialchars($post['summary'])) ?>
            </div>

            <img src="<?= htmlspecialchars($post['thumbnail_URL']) ?>" class="img-fluid rounded mb-4 w-100" alt="Thumbnail">

            <div class="article-content">
                <?= $post['content'] ?>
            </div>

            <?php if (!empty($tags)): ?>
                <div class="mt-4 pt-3 mb-2">
                    <?php foreach ($tags as $tag): ?>
                        <span class="badge bg-light text-dark border me-1 py-2 px-3 rounded-pill">#<?= htmlspecialchars($tag['slug']) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="d-flex mt-5 mb-4 pb-4 border-bottom border-top pt-4 align-items-center justify-content-between">

                <div class="d-flex">
                    <button
                        id="btn-like"
                        class="btn border fw-bold me-3 px-4 <?= $isLiked ? 'active-like' : 'btn-light text-muted' ?>"
                        onclick="handleLike()"
                        style="transition: 0.3s; <?= $isLiked ? 'background:#003049;color:#fff;' : '' ?>">
                        <i class="fa-thumbs-up me-2 <?= $isLiked ? 'fa-solid' : 'fa-regular' ?>"></i>
                        <span><?= $isLiked ? 'ĐÃ THÍCH' : 'THÍCH' ?></span>
                    </button>

                    <button
                        id="btn-save"
                        class="btn border fw-bold px-4 <?= $isSaved ? 'active-save' : 'btn-light text-muted' ?>"
                        onclick="handleSave()"
                        style="transition: 0.3s; <?= $isSaved ? 'background:#B90C17;color:#fff;' : '' ?>">
                        <i class="fa-bookmark me-2 <?= $isSaved ? 'fa-solid' : 'fa-regular' ?>"></i>
                        <span><?= $isSaved ? 'ĐÃ LƯU' : 'LƯU' ?></span>
                    </button>
                </div>

                <div class="text-muted">
                    <i class="fa-regular fa-eye me-1"></i> <?= $post['view_count'] ?> lượt xem
                </div>
            </div>

            <div class="comments-section mt-5" id="comment-section">
                <h3 id="comment-count-title" class="fw-bold mb-4" style="color: var(--navy); font-family: 'Newsreader', serif;" data-count="<?= $totalComments ?>">Bình luận (<?= $totalComments ?>)</h3>
                <div class="comment-input-box p-4 mb-5">
                    <textarea id="comment-content"

                        class="form-control border-0 bg-white" rows="3"
                        placeholder="Chia sẻ ý kiến của bạn..." style="resize: none;"></textarea>
                    <div class="text-end mt-3">
                        <button class="btn btn-send px-4 fw-bold py-2 rounded" onclick="submitComment()">Gửi bình
                            luận</button>
                    </div>
                </div>

                <div class="comment-area">
                    <?php if (empty($comments)): ?>
                        <p class="text-muted text-center py-4 bg-light rounded">Chưa có bình luận nào. Hãy là người đầu tiên
                            để lại ý kiến!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $cmt): ?>
                            <div class="d-flex mb-4 pb-4 border-bottom">
                                <img src="<?= $defaultAvatar ?>" class="comment-avatar me-3" alt="Avatar">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-bold" style="color: var(--navy);">
                                            <?= htmlspecialchars($cmt['full_name']) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?>
                                        </div>
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($cmt['content'])) ?>
                                    </div>
                                    <a href="#" class="text-danger fw-bold text-decoration-none mt-2 d-inline-block"
                                        style="font-size: 0.8rem; color: var(--red) !important;">TRẢ LỜI</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div id="comment-pagination-wrapper">
                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex justify-content-center align-items-center mt-4 gap-3">

                            <?php if ($page > 1): ?>
                                <button onclick="loadComments(<?= $page - 1 ?>)" class="btn btn-pagination-arrow">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                            <?php endif; ?>

                            <span class="comment-pagination-text">Trang <?= $page ?> / <?= $totalPages ?></span>

                            <?php if ($page < $totalPages): ?>
                                <button onclick="loadComments(<?= $page + 1 ?>)" class="btn btn-pagination-arrow">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <h3 class="sidebar-title text-uppercase mb-4" style="font-family: 'Barlow', sans-serif; border-bottom: 2px solid var(--navy); padding-bottom: 10px; color: var(--navy);">
                Cùng chuyên mục
            </h3>

            <div class="recommended-list mb-5">
                <?php if (empty($recommendedPosts)): ?>
                    <p class="text-muted small">Chưa có bài viết liên quan.</p>
                <?php else: ?>
                    <?php foreach ($recommendedPosts as $rec): ?>
                        <div class="d-flex mb-4" style="cursor: pointer;" onclick="window.location.href='index.php?page=post&id=<?= $rec['post_id'] ?>'">
                            <img src="<?= htmlspecialchars($rec['thumbnail_URL']) ?>" class="rounded me-3" alt="Thumb" style="width: 100px; height: 70px; object-fit: cover; border: 1px solid #eee;">
                            <div>
                                <div class="fw-bold mb-1" style="color: #111; font-size: 0.95rem; line-height: 1.3; font-family: 'Newsreader', serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($rec['title']) ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-eye me-1"></i> <?= number_format($rec['view_count'] ?? 0) ?> lượt xem
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <h3 class="sidebar-title text-uppercase mb-4" style="font-family: 'Barlow', sans-serif; border-bottom: 2px solid var(--red); padding-bottom: 10px; color: var(--red);">
                <i class="fa-solid fa-fire-flame-curved me-2"></i> Xu hướng
            </h3>

            <div class="trending-sidebar-list">
                <?php if (empty($trendingGlobal)): ?>
                    <p class="text-muted small">Đang cập nhật...</p>
                <?php else: ?>
                    <?php foreach ($trendingGlobal as $index => $t): ?>
                        <div class="d-flex mb-4 align-items-start" style="cursor: pointer;" onclick="window.location.href='index.php?page=post&id=<?= $t['post_id'] ?>'">
                            <span class="fw-bold me-3" style="font-size: 1.6rem; font-family: 'Barlow'; color: <?= $index < 3 ? 'var(--red)' : '#ccc' ?>; line-height: 1;">
                                0<?= $index + 1 ?>
                            </span>
                            <div class="border-bottom pb-3 w-100" style="border-color: #f1f1f1 !important;">
                                <div class="fw-bold mb-1" style="color: var(--navy); font-size: 0.95rem; line-height: 1.4; font-family: 'Newsreader', serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($t['title']) ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="text-uppercase fw-bold" style="font-size: 0.65rem; color: #b90c17;"><?= htmlspecialchars($t['category_name']) ?></span>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fa-regular fa-eye me-1"></i> <?= number_format($t['view_count'] ?? 0) ?> lượt xem

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const currentPostId = '<?= $post['post_id'] ?>';
    const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

    // Flags để prevent do isLikeProcessing = false;
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
</script>
<?php
include __DIR__ . '/../../Partials/Client/Footer.php';
?>