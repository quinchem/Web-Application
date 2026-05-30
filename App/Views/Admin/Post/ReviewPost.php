<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Duyệt bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/ReviewPost.css?v=<?= time() ?>">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Sidebar.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Header.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Footer.css">
    <style>
        html, body { height: 100%; overflow: hidden; }
        .admin-layout { height: 100vh; overflow: hidden; }
        .sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; flex-shrink: 0; }
        .main-content { height: 100vh; overflow-y: auto; flex: 1; }
        .profile-wrapper { padding: 16px 18px; }
        .admin-profile { padding: 14px 16px; gap: 12px; border-radius: 14px; }
        .admin-profile img { width: 40px; height: 40px; }
        .profile-info strong { font-size: 14px; margin-bottom: 6px; }
        .profile-info p { font-size: 11px; gap: 7px; }
        .profile-info i { font-size: 13px; }
        .custom-modal-backdrop,
        #changePasswordModal,
        #editProfileModal {
            position: fixed !important;
            inset: 0 !important;
            z-index: 999999 !important;
        }
    </style>
    <script src="Public/Admin/Js/Pages/ReviewPost.js?v=<?= time() ?>" defer></script>
    <script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>
</head>
<body>
<div class="admin-layout">

    <?php require_once __DIR__ . '/../../Partials/Admin/Sidebar.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div class="breadcrumb">
                <a href="Admin_index.php?page=admin_user_posts">QUẢN LÝ BÀI VIẾT</a>
                <span>></span>
                <a href="Admin_index.php?page=admin_user_posts">BÀI VIẾT NGƯỜI ĐỌC</a>
                <span>></span>
                <span class="active">DUYỆT BÀI VIẾT</span>
            </div>
        </div>

        <section class="content-inner review-page-inner">
            <div class="review-page-header">
                <h1>DUYỆT BÀI VIẾT</h1>
            </div>

            <?php if (!empty($post)): ?>

                <!-- TIÊU ĐỀ -->
                <div class="rv-card">
                    <span class="rv-section-label">TIÊU ĐỀ BÀI VIẾT</span>
                    <h2 class="rv-title"><?= htmlspecialchars($post['title'] ?? '') ?></h2>
                </div>

                <!-- TÓM TẮT -->
                <div class="rv-card">
                    <div class="rv-summary-label">TÓM TẮT NỘI DUNG</div>
                    <p class="rv-summary-text"><?= strip_tags(preg_replace('/\s*color\s*:\s*[^;"\'>]+[;]?/i', '', $post['summary'] ?? '')) ?></p>
                </div>

                <!-- ẢNH ĐẠI DIỆN -->
                <div class="rv-card">
                    <span class="rv-section-label"><i class="fa-regular fa-image"></i> ẢNH ĐẠI DIỆN BÀI VIẾT</span>
                    <?php if (!empty($post['thumbnail_URL'])): ?>
                        <img src="<?= htmlspecialchars($post['thumbnail_URL']) ?>"
                             class="rv-thumbnail" alt="Thumbnail">
                    <?php else: ?>
                        <div class="rv-thumbnail-placeholder">
                            <i class="fa-regular fa-image"></i>
                            <span>Bài viết chưa có ảnh đại diện</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- NỘI DUNG -->
                <div class="rv-card">
                    <span class="rv-section-label">NỘI DUNG BÀI VIẾT</span>
                    <div class="rv-content"><?= $post['content'] ?? '' ?></div>
                </div>

                <!-- META -->
                <div class="rv-card">
                    <div class="rv-meta-grid">
                        <div>
                            <span class="rv-meta-label">DANH MỤC</span>
                            <div class="rv-meta-value">
                                <?= htmlspecialchars($post['parent_category_name'] ?? $post['category_name'] ?? '—') ?>
                            </div>
                        </div>
                        <div>
                            <span class="rv-meta-label">DANH MỤC CON</span>
                            <div class="rv-meta-value">
                                <?= htmlspecialchars($post['category_name'] ?? '—') ?>
                            </div>
                        </div>
                        <div>
                            <span class="rv-meta-label">TÁC GIẢ</span>
                            <div class="rv-meta-value">
                                <?= htmlspecialchars($post['author_name'] ?? '—') ?>
                            </div>
                        </div>
                        <div>
                            <span class="rv-meta-label">TAGS BÀI VIẾT</span>
                            <div class="rv-tags">
                                <?php if (!empty($tags)): ?>
                                    <?php foreach ($tags as $tag):
                                        $tagVal = is_array($tag) ? ($tag['slug'] ?? $tag['name'] ?? '') : $tag;
                                    ?>
                                        <span class="rv-tag-chip"><?= htmlspecialchars($tagVal) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span style="color:#9fb0bc;font-size:13px;">Chưa có tag</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUYẾT ĐỊNH PHÊ DUYỆT — inline -->
                <div class="rv-card rv-decision-card">
                    <p class="rv-section-label">QUYẾT ĐỊNH PHÊ DUYỆT</p>
                    <div class="rv-decision-grid">
                        <button class="decision-btn approve selected" onclick="selectDecision('approved')">
                            <i class="fa-regular fa-circle-check"></i> Duyệt
                        </button>
                        <button class="decision-btn reject" onclick="selectDecision('rejected')">
                            <i class="fa-regular fa-circle-xmark"></i> Chưa duyệt
                        </button>
                    </div>

                    <p class="rv-section-label" style="margin-top:24px;">LÝ DO & GHI CHÚ</p>
                    <textarea class="rv-textarea" id="modal-reason"
                        placeholder="Nhập lý do chưa duyệt cho tác giả..."></textarea>

                    <input type="hidden" id="modal-post-id"
                        value="<?= htmlspecialchars($post['post_id']) ?>">
                    <input type="hidden" id="modal-decision" value="approved">
                </div>

                <!-- FOOTER ACTIONS -->
                <div class="rv-action-bar">
                    <a href="Admin_index.php?page=admin_user_posts" class="rv-btn-cancel">HỦY BỎ</a>
                    <button class="rv-btn-review" onclick="submitReview()">XÁC NHẬN</button>
                </div>

            <?php else: ?>
                <div class="rv-card" style="text-align:center;padding:48px;color:#aaa;">
                    Không tìm thấy bài viết.
                </div>
            <?php endif; ?>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/../Profile/edit.php'; ?>
<?php require_once __DIR__ . '/../Profile/change_password.php'; ?>

</body>
</html>