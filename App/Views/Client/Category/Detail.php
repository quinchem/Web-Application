<?php
// Detail.php
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&family=Newsreader:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="Public/Client/Css/Category.css">

    <title>
        <?= htmlspecialchars($categoryName ?? '') ?> - Trạm Tin Việt
    </title>
</head>

<body>

<?php include __DIR__ . '/../../Partials/Client/Header.php'; ?>
<div class= "background" style="width: 100%; height: 100%; background-color: #F0F8FF !important;">
<main class="container category-wrapper page-spacing" style="background-color: #F0F8FF !important;">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-custom">

        <a href="index.php?page=homepage" class="breadcrumb-link">
            Trang chủ
        </a>

        <span class="mx-2">></span>

        <a href="index.php?page=category_detail&slug=<?= urlencode($categorySlug ?? '') ?>"
        class="breadcrumb-current">
            <?= htmlspecialchars($categoryName ?? '') ?>
        </a>

    </div>

    <!-- HERO -->
    <div class="category-hero">

        <h1 class="category-title">
            <?= htmlspecialchars($categoryName ?? '') ?>
        </h1>

        <?php if (!empty($categoryDesc)): ?>
            <p class="category-desc">
                <?= htmlspecialchars($categoryDesc) ?>
            </p>
        <?php endif; ?>

    </div>

    <!-- CONTENT -->
    <?php if (!empty($posts)): ?>

        <?php foreach ($posts as $subName => $subPosts): ?>

            <?php
                if (empty($subPosts)) continue;
                $subPosts = array_values($subPosts);
                $first = array_shift($subPosts);

                // Tác giả bài featured
               $firstAuthorLabel = (($first['author_role'] ?? '') === 'admin')
            ? 'Biên tập viên: ' . ucwords(mb_strtolower($first['author_name'] ?? '', 'UTF-8'))
            : ucwords(mb_strtolower($first['author_name'] ?? '', 'UTF-8'));
            ?>
            <section class="news-section">

                <div class="d-flex justify-content-between align-items-end section-title-wrap">

                    <h2 class="section-title">
                        <?= htmlspecialchars($subName) ?>
                    </h2>
<a href="index.php?page=subcategory&parent=<?= urlencode($categorySlug ?? '') ?>&slug=<?= urlencode($first['category_slug'] ?? '') ?>"
   class="view-more">
    XEM THÊM
</a>

                </div>

                <div class="row g-4 g-lg-5">

                    <!-- LEFT -->
                    <div class="col-lg-7">

                        <a href="index.php?page=post&id=<?= $first['post_id'] ?>"
                           class="text-decoration-none text-dark d-block">

                            <img src="<?= htmlspecialchars($first['thumbnail_url'] ?? '') ?>"
                                 class="featured-image"
                                 alt="">

                            <span class="post-category">
                                <?= htmlspecialchars($first['category_name'] ?? '') ?>
                            </span>

                            <h3 class="featured-title">
                                <?= htmlspecialchars($first['title'] ?? '') ?>
                            </h3>

                            <p class="featured-summary">
                                <?= htmlspecialchars($first['summary'] ?? '') ?>
                            </p>

                        </a>

                        <div class="post-meta">

                            <?= !empty($first['published_at'])
                                ? date('d/m/Y', strtotime($first['published_at']))
                                : '' ?>

                            <span class="dot">·</span>

                            <?= htmlspecialchars($firstAuthorLabel) ?>

                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-5">

                        <div class="side-wrapper">

                            <?php foreach ($subPosts as $p): ?>

                                <?php
                                   $sideAuthorLabel = (($p['author_role'] ?? '') === 'admin')
                                    ? 'Biên tập viên: ' . ucwords(mb_strtolower($p['author_name'] ?? '', 'UTF-8'))
                                    : ucwords(mb_strtolower($p['author_name'] ?? '', 'UTF-8'));
                                ?>

                                <div class="side-post">

                                    <span class="post-category">
                                        <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                    </span>

                                    <a href="index.php?page=post&id=<?= $p['post_id'] ?>"
                                       class="text-decoration-none text-dark d-block">

                                        <h4 class="side-title clamp-2">
                                            <?= htmlspecialchars($p['title'] ?? '') ?>
                                        </h4>

                                        <p class="side-summary clamp-3">
                                            <?= htmlspecialchars($p['summary'] ?? '') ?>
                                        </p>

                                    </a>

                                    <div class="post-meta">

                                        <?= !empty($p['published_at'])
                                            ? date('d/m/Y', strtotime($p['published_at']))
                                            : '' ?>

                                        <span class="dot">·</span>

                                        <?= htmlspecialchars($sideAuthorLabel) ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>

            </section>

        <?php endforeach; ?>

    <?php endif; ?>

</main>
</div>

<?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

<script src="Public/Client/Js/Category.js"></script>

</body>
</html>