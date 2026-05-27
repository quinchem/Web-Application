<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Newsreader:ital,wght@0,700;1,700&family=Barlow:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Public/Client/Css/Category.css">
    <title><?= htmlspecialchars($categoryName ?? 'Danh mục') ?> – Trạm Tin Việt</title>
</head>

<?php
/**
 * Detail.php – Trang chi tiết danh mục cha
 *
 * Biến nhận từ PostController::category():
 *   $categoryName  (string)  – Tên danh mục cha, vd: "Thời sự"
 *   $categoryDesc  (string)  – Mô tả danh mục (tuỳ chọn)
 *   $posts         (array)   – Dạng ['Sub-category' => [ [...], [...] ], ...]
 */
include __DIR__ . '/../../Partials/Client/Header.php';
?>

<main class="container bg-white my-5 category-main">

    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="index.php?page=homepage">Trang chủ</a>
        <span class="mx-1">›</span>
        <strong><?= htmlspecialchars($categoryName ?? '') ?></strong>
    </nav>

    <!-- Category hero -->
    <div class="cat-hero">
        <h1 class="cat-title"><?= htmlspecialchars($categoryName ?? '') ?></h1>
        <?php if (!empty($categoryDesc)): ?>
            <p class="cat-desc"><?= htmlspecialchars($categoryDesc) ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $subName => $subPosts): ?>
            <?php if (empty($subPosts)) continue; ?>

            <section class="sub-cat-section">

                <!-- Section header -->
                <div class="d-flex justify-content-between align-items-end section-title-border mb-4 pb-2">
                    <h2 class="h3 fw-bold m-0 section-heading">
                        <?= htmlspecialchars($subName) ?>
                    </h2>
                    <a href="index.php?page=category&name=<?= urlencode($subName) ?>" class="view-more-link">
                        XEM THÊM
                    </a>
                </div>

                <?php
                    $subPosts = array_values($subPosts);
                    $first    = array_shift($subPosts);
                ?>

                <div class="row g-4 g-lg-5">

                    <!-- Featured post (left) -->
                    <div class="col-12 col-lg-7">
                        <a href="index.php?page=post&id=<?= $first['post_id'] ?>" class="text-decoration-none text-dark d-block">
                            <div class="featured-img-wrap">
                                <img src="<?= htmlspecialchars($first['thumbnail_url'] ?? '') ?>"
                                     class="w-100 post-main-img shadow-sm" alt="Thumbnail">
                            </div>
                            <span class="cat-label mt-3 d-block">
                                <?= htmlspecialchars($first['category_name'] ?? $subName) ?>
                            </span>
                            <h3 class="post-title-featured">
                                <?= htmlspecialchars($first['title'] ?? '') ?>
                            </h3>
                            <p class="post-summary">
                                <?= htmlspecialchars($first['summary'] ?? '') ?>
                            </p>
                        </a>
                        <div class="post-meta">
                            <span><?= !empty($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                            <?php if (!empty($first['author_name'])): ?>
                                <span class="meta-dot">·</span>
                                <span><?= htmlspecialchars($first['author_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Side posts (right) -->
                    <?php if (!empty($subPosts)): ?>
                    <div class="col-12 col-lg-5">
                        <div class="d-flex flex-column">
                            <?php foreach ($subPosts as $p): ?>
                                <div class="side-post-card">
                                    <span class="cat-label">
                                        <?= htmlspecialchars($p['category_name'] ?? $subName) ?>
                                    </span>
                                    <a href="index.php?page=post&id=<?= $p['post_id'] ?>" class="text-decoration-none text-dark d-block">
                                        <h4 class="post-title-small clamp-2">
                                            <?= htmlspecialchars($p['title'] ?? '') ?>
                                        </h4>
                                        <p class="post-summary-small clamp-3">
                                            <?= htmlspecialchars($p['summary'] ?? '') ?>
                                        </p>
                                    </a>
                                    <div class="post-meta">
                                        <span><?= !empty($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                                        <?php if (!empty($p['author_name'])): ?>
                                            <span class="meta-dot">·</span>
                                            <span><?= htmlspecialchars($p['author_name']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </section>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 text-muted empty-state">
            <i class="fas fa-newspaper fa-3x mb-3"></i>
            <p>Chưa có bài viết nào trong danh mục này.</p>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

<script src="/Public/Client/Js/Category.js"></script>
</html>