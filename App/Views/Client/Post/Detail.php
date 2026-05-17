<?php 
require_once __DIR__ . '/../../Partials/Client/Header.php'; 

$defaultAvatar = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .breadcrumb {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .article-title {
        font-size: 2.8rem;
        font-family: 'Newsreader', serif;
        line-height: 1.2;
        color: #111;
        margin-top: 10px;
    }

    .sapo-box {
        font-style: italic;
        font-family: 'Montserrat', sans-serif;
        color: #5a7d9a;
        font-size: 1.15rem;
        border-left: 4px solid #B90C17;
        padding-left: 20px;
        line-height: 1.7;
    }

    .article-content p {
        font-size: 1.1rem;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .sidebar-title {
        font-size: 1.1rem;
        font-family: 'Barlow', sans-serif;
        font-weight: 700;
        border-left: 5px solid #B90C17;
        padding-left: 12px;
        color: #111;
        margin-bottom: 20px;
    }

    .sidebar-thumb {
        width: 85px;
        height: 65px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .comment-input-box {
        background-color: #F2F4F5;
        border-radius: 8px;
    }

    .comment-avatar {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        flex-shrink: 0;
    }

    .btn-send {
        background-color: #003049;
        border: none;
        font-family: 'Barlow', sans-serif;
        color: white;
    }
    
    .btn-send:hover {
        background-color: #001f33;
    }

    /* CSS giới hạn nội dung tóm tắt bài viết đề xuất trong vòng 2 dòng */
    .summary-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.85rem;
        color: #5a7d9a;
        margin-top: 5px;
        line-height: 1.5;
    }
</style>

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
                        <div class="fw-bold fs-6" style="color: var(--navy);"><?= htmlspecialchars($post['author_name']) ?></div>
                        <div class="text-muted mt-1" style="font-size: 0.85rem;">
                            <i class="fa-regular fa-calendar me-1"></i> <?= date('d/m/Y H:i', strtotime($post['published_at'])) ?>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <?php if (!empty($post['parent_category_name'])): ?>
                            <span class="badge border text-muted bg-light me-1 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
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

            <?php if(!empty($tags)): ?>
            <div class="mt-4 pt-3 mb-2">
                <?php foreach($tags as $tag): ?>
                    <span class="badge bg-light text-dark border me-1 py-2 px-3 rounded-pill">#<?= htmlspecialchars($tag['slug']) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="d-flex mt-5 mb-4 pb-4 border-bottom border-top pt-4">
                <button id="btn-like" class="btn btn-light border fw-bold me-3 text-muted px-4" onclick="handleLike()" style="transition: 0.3s;">
                    <i class="fa-regular fa-thumbs-up me-2"></i><span>THÍCH</span>
                </button>
                <button id="btn-save" class="btn btn-light border fw-bold text-muted px-4" onclick="handleSave()" style="transition: 0.3s;">
                    <i class="fa-regular fa-bookmark me-2"></i><span>LƯU</span>
                </button>
            </div>

            <div class="comments-section mt-5" id="comment-section">
                <h3 class="fw-bold mb-4" style="color: var(--navy); font-family: 'Newsreader', serif;">Bình luận (<?= $totalComments ?>)</h3>
                
                <div class="comment-input-box p-4 mb-5">
                    <textarea id="comment-content" class="form-control border-0 bg-white" rows="3" placeholder="Chia sẻ ý kiến của bạn..." style="resize: none;"></textarea>
                    <div class="text-end mt-3">
                        <button class="btn btn-send px-4 fw-bold py-2 rounded" onclick="submitComment()">Gửi bình luận</button>
                    </div>
                </div>

                <div class="comment-area">
                    <?php if (empty($comments)): ?>
                        <p class="text-muted text-center py-4 bg-light rounded">Chưa có bình luận nào. Hãy là người đầu tiên để lại ý kiến!</p>
                    <?php else: ?>
                        <?php foreach($comments as $cmt): ?>
                            <div class="d-flex mb-4 pb-4 border-bottom">
                                <img src="<?= $defaultAvatar ?>" class="comment-avatar me-3" alt="Avatar">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-bold" style="color: var(--navy);"><?= htmlspecialchars($cmt['full_name']) ?></div>
                                        <div class="small text-muted"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></div>
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem; line-height: 1.6;"><?= nl2br(htmlspecialchars($cmt['content'])) ?></div>
                                    <a href="#" class="text-danger fw-bold text-decoration-none mt-2 d-inline-block" style="font-size: 0.8rem; color: var(--red) !important;">TRẢ LỜI</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if($totalPages > 1): ?>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <?php if($page > 1): ?>
                        <a href="index.php?page=post&id=<?= $post['post_id'] ?>&cpage=<?= $page - 1 ?>#comment-section" class="btn btn-sm btn-outline-secondary me-2"><i class="fa-solid fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <span class="mx-3 fw-bold text-muted small">Trang <?= $page ?> / <?= $totalPages ?></span>
                    <?php if($page < $totalPages): ?>
                        <a href="index.php?page=post&id=<?= $post['post_id'] ?>&cpage=<?= $page + 1 ?>#comment-section" class="btn btn-sm btn-outline-secondary ms-2"><i class="fa-solid fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <h3 class="sidebar-title text-uppercase">BÀI VIẾT ĐỀ XUẤT</h3>
            
            <div class="mt-4">
                <?php foreach($recommendedPosts as $rec): ?>
                    <div class="d-flex mb-4" style="cursor: pointer;" onclick="window.location.href='index.php?page=post&id=<?= $rec['post_id'] ?>'">
                        <img src="<?= htmlspecialchars($rec['thumbnail_URL']) ?>" class="sidebar-thumb rounded me-3" alt="Thumb">
                        <div>
                            <div class="fw-bold mb-1" style="color: #111; font-size: 0.95rem; line-height: 1.3; font-family: 'Newsreader', serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($rec['title']) ?>
                            </div>
                            <div class="summary-clamp"><?= htmlspecialchars($rec['summary']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
    const currentPostId = '<?= $post['post_id'] ?>';

    function handleLike() {
        let formData = new FormData();
        formData.append('post_id', currentPostId);
        fetch('index.php?page=api_like', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
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
        let formData = new FormData();
        formData.append('post_id', currentPostId);
        fetch('index.php?page=api_save', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
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
        const content = document.getElementById('comment-content').value.trim();
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
            if(data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Thành công', text: 'Bình luận của bạn đã được đăng tải thành công!', timer: 1500, showConfirmButton: false })
                .then(() => { location.reload(); });
            } else {
                Swal.fire('Lỗi', data.message, 'error');
            }
        });
    }
</script>

<?php 
require_once __DIR__ . '/../../Partials/Client/Footer.php'; 
?>