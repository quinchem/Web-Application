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

            <div class="breadcrumb-custom">
                <a href="index.php?page=homepage" class="breadcrumb-link">
                    Trang chủ
                </a>

                <?php if (!empty($post['parent_category_name'])): ?>
                    <span class="mx-1">></span>
                    <a href="index.php?page=category_detail&name=<?= urlencode($post['parent_category_name']) ?>"
                        class="breadcrumb-parent">
                        <?= htmlspecialchars($post['parent_category_name']) ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($post['category_name'])): ?>
                    <span class="mx-1">></span>
                    <span class="breadcrumb-current-tag">
                        <?= htmlspecialchars($post['category_name']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="article-title fw-bold"><?= htmlspecialchars($post['title']) ?></h1>

            <div class="d-flex align-items-center mb-4 pb-3 border-bottom mt-4">
                <img src="<?= !empty($post['avatar']) ? htmlspecialchars($post['avatar']) : htmlspecialchars($defaultAvatar) ?>"
                    alt="Avatar"
                    class="rounded-circle me-3"
                    style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #eee;">

                <div class="w-100 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-6" style="color: var(--navy);">
                            <?= htmlspecialchars($post['author_name']) ?>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.85rem;">
                            <i class="fa-regular fa-calendar me-1"></i>
                            <?= !empty($post['published_at'])
                                ? date('d/m/Y H:i', strtotime($post['published_at']))
                                : date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <?php if (!empty($post['parent_category_name'])): ?>
                            <span class="post-parent-category">
                                <?= htmlspecialchars($post['parent_category_name']) ?>
                            </span>
                        <?php endif; ?>

                        <span class="post-child-category">
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
                        <button class="btn px-4 fw-bold py-2 rounded btn-comment-submit" onclick="submitComment()">
                            Gửi bình luận
                        </button>
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
                                    <a href="#" class="reply-btn fw-bold text-decoration-none mt-2 d-inline-block"
                                        style="font-size:0.8rem; color:var(--red) !important;"
                                        data-comment-id="<?= htmlspecialchars($cmt['comment_id']) ?>"
                                        data-author="<?= htmlspecialchars($cmt['full_name']) ?>">
                                        TRẢ LỜI
                                    </a>
                                    <div class="reply-form mt-2" style="display:none;">
                                        <textarea class="form-control reply-input" rows="2"
                                            placeholder="Trả lời <?= htmlspecialchars($cmt['full_name']) ?>..."></textarea>
                                        <div class="text-end mt-2">
                                            <button class="btn btn-sm btn-secondary reply-cancel-btn me-2">Huỷ</button>
                                            <button class="btn btn-sm btn-comment-submit reply-submit-btn"
                                                data-comment-id="<?= htmlspecialchars($cmt['comment_id']) ?>">
                                                Gửi
                                            </button>
                                        </div>
                                    </div>
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
    window.POST_DETAIL_CONFIG = {
        currentPostId: "<?= htmlspecialchars($post['post_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>",
        isLoggedIn: <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>
    };
</script>

<script src="Public/Client/Js/PostDetail.js"></script>
<?php
include __DIR__ . '/../../Partials/Client/Footer.php';
?>