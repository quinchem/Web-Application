<?php
/**
 * @var array  $posts         Danh sách bài viết trong danh mục cấp 2
 * @var string $categoryName  Tên danh mục hiện tại
 */

$categoryName = $categoryName ?? ($posts[0]['category_name'] ?? '');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($categoryName) ?> - Trạm Tin Việt
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&family=Newsreader:wght@400;600;700&display=swap"
          rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
          href="Public/Client/Css/DetailCategory.css">
</head>

<body>

<?php include __DIR__ . '/../../Partials/Client/Header.php'; ?>

<main class="container detail-wrapper page-spacing">

    <!-- BREADCRUMB -->
<div class="breadcrumb-custom">

    <a href="index.php?page=homepage" class="breadcrumb-link">
        Trang chủ
    </a>

    <?php
    $parentName = $posts[0]['parent_category_name'] ?? '';
    $parentSlug = $posts[0]['parent_category_slug'] ?? '';
    if (!empty($parentName)): ?>
        <span class="mx-2">></span>
        <a href="index.php?page=category_detail&slug=<?= urlencode($parentSlug) ?>"
           class="breadcrumb-parent">
            <?= htmlspecialchars($parentName) ?>
        </a>
    <?php endif; ?>

    <span class="mx-2">></span>

    <a href="index.php?page=subcategory&slug=<?= urlencode($posts[0]['category_slug'] ?? '') ?>"
       class="breadcrumb-current-tag">
        <?= htmlspecialchars($categoryName) ?>
    </a>

</div>

    <!-- TIÊU ĐỀ -->
    <div class="category-hero">
        <h1 class="category-title">
            <?= htmlspecialchars($categoryName) ?>
        </h1>
    </div>

    <!-- DANH SÁCH BÀI VIẾT -->
<?php if (!empty($featuredPost)): ?>

<section class="featured-post">

    <a href="index.php?page=post&id=<?= htmlspecialchars($featuredPost['post_id']) ?>"
       class="featured-image-link">

        <img src="<?= htmlspecialchars($featuredPost['thumbnail_url'] ?? '') ?>"
     class="featured-image"
     alt="">

    </a>

    <div class="featured-content">

        <div class="article-category">
            <?= htmlspecialchars($featuredPost['category_name'] ?? '') ?>
        </div>

        <a href="index.php?page=post&id=<?= htmlspecialchars($featuredPost['post_id']) ?>"
           class="text-decoration-none">

            <h1 class="featured-title">

                <?= htmlspecialchars($featuredPost['title'] ?? '') ?>

            </h1>

        </a>

        <p class="featured-summary">

            <?= htmlspecialchars($featuredPost['summary'] ?? '') ?>

        </p>

        <div class="article-meta">

            <span>
                <?= !empty($featuredPost['published_at'])
                    ? timeAgo($featuredPost['published_at'])
                    : '' ?>
            </span>

            <span class="dot">·</span>

            <span>
                <?= htmlspecialchars($featuredPost['author_name'] ?? '') ?>
            </span>

        </div>

    </div>

</section>

<?php endif; ?>

<?php if (!empty($posts)): ?>

<section class="related-section">

    <?php foreach ($posts as $item): ?>

        <article class="related-item">

            <a href="index.php?page=post&id=<?= htmlspecialchars($item['post_id']) ?>"
               class="related-image-wrap">

                <img src="<?= htmlspecialchars($item['thumbnail_url'] ?? '') ?>"
                class="related-image"
                alt="">

            </a>

            <div class="related-content">

                <div class="article-category">
                    <?= htmlspecialchars($item['category_name'] ?? '') ?>
                </div>

                <a href="index.php?page=post&id=<?= htmlspecialchars($item['post_id']) ?>"
                   class="text-decoration-none">

                    <h3 class="related-title clamp-2">

                        <?= htmlspecialchars($item['title'] ?? '') ?>

                    </h3>

                </a>

                <p class="related-summary clamp-3">

                    <?= htmlspecialchars($item['summary'] ?? '') ?>

                </p>

                <div class="article-meta">

                    <span>
                        <?= !empty($item['published_at'])
                            ? timeAgo($item['published_at'])
                            : '' ?>
                    </span>

                    <span class="dot">·</span>

                    <span>
                        <?= htmlspecialchars($item['author_name'] ?? '') ?>
                    </span>

                </div>

            </div>

        </article>

    <?php endforeach; ?>

</section>

<?php endif; ?>

</main>

<?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

<script src="Public/Client/Js/DetailCategory.js"></script>

</body>
</html>