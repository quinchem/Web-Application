<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/Home.css">
</head>

<?php
include __DIR__ . '/../Partials/Client/Header.php';

// Hàm tiện ích để hiển thị thời gian theo dạng "x phút trước", "x giờ trước", v.v.
function resultTimeAgo($datetime)
{
    if (empty($datetime)) {
        return '';
    }

    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Vừa xong';
    }

    if ($diff < 3600) {
        return floor($diff / 60) . ' phút trước';
    }

    if ($diff < 86400) {
        return floor($diff / 3600) . ' giờ trước';
    }

    if ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days === 1 ? 'Hôm qua' : $days . ' ngày trước';
    }

    return date('d/m/Y', $timestamp);
}

// Hàm tiện ích để hiển thị tên tác giả, phân biệt BTV và người
function displayAuthor($post)
{
    $name = htmlspecialchars($post['author_name'] ?? '');

    if (($post['role_id'] ?? '') === 'RL0001') {
        return 'BTV Trạm Tin Việt: ' . $name;
    }

    return $name;
}
?>

<main class="container my-5 home-main" style="width: 1140px; max-width: 1140px; min-height: 100vh;">

<?php if (isset($bannerPosts) && !empty($bannerPosts)): ?>
    <section id="heroCarousel"
             class="carousel slide hero-section mb-5 shadow"
             data-bs-ride="carousel"
             data-bs-interval="4000">

        <div class="carousel-indicators hero-indicators">
            <?php foreach ($bannerPosts as $index => $post): ?>
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>"
                        aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>">
                </button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner h-100">
            <?php foreach ($bannerPosts as $index => $post): ?>
                <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                    <a href="index.php?page=post&id=<?= $post['post_id'] ?>" class="text-decoration-none d-block h-100">
                        <img src="<?= htmlspecialchars($post['thumbnail_url'] ?? '') ?>"
                             class="hero-img">

                        <div class="hero-gradient"></div>

                        <div class="hero-content text-white">
                            <div>
                                <span class="hero-label bg-danger text-white px-3 py-2 mb-4 d-inline-block fw-bold text-uppercase tracking-wider">
                                    Tiêu điểm
                                </span>
                            </div>

                            <h1 class="hero-title display-6 fw-bold mb-4 style-serif">
                                <?= htmlspecialchars($post['title'] ?? '') ?>
                            </h1>

                            <p class="hero-summary fs-5 fw-light mb-4 clamp-4">
                                <?= htmlspecialchars($post['summary'] ?? '') ?>
                            </p>

                            <div class="read-more-btn d-flex align-items-center gap-2 text-white text-sm fw-medium cursor-pointer">
                                <span>Đọc toàn bộ bài viết</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke-width="2"
                                     stroke="currentColor"
                                     class="read-more-icon">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev hero-carousel-control"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next hero-carousel-control"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </section>
<?php endif; ?>

    <section class="mb-5 pb-4">
        <div class="d-flex justify-content-between align-items-end section-title-border mb-4 pb-2">
            <h2 class="section-heading h3 fw-bold m-0">Thời sự</h2>
            <a href="index.php?page=category_detail&slug=<?= urlencode($thoiSuSlug ?? 'thoi-su') ?>" class="section-more text-uppercase fw-bold text-decoration-none tracking-widest small" style="color: #b90c17;">
                Xem thêm
            </a>
        </div>

        <div class="row g-4 g-lg-5">
            <?php if (!empty($thoiSu)):
                $first = array_shift($thoiSu); ?>
                <div class="col-12 col-lg-7">
                    <a href="index.php?page=post&id=<?= $first['post_id'] ?>" class="text-decoration-none text-dark d-block">
                        <img src="<?= ($first['thumbnail_url'] ?? '') ?>" class="w-100 post-main-img mb-3 shadow-sm" alt="Thumbnail">
                        <div class="d-flex align-items-center gap-3 my-2 text-muted small">
                            <span class="main-category fw-bold text-uppercase">
                                <?= htmlspecialchars($first['category_name'] ?? '') ?>
                            </span>
                        </div>
                        <h3 class="post-title h3 fw-bold mb-3">
                            <?= htmlspecialchars($first['title'] ?? '') ?>
                        </h3>
                        <p class="post-summary"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    </a>
                    <div class="post-meta d-flex align-items-center gap-2 mt-2 text-uppercase text-muted fw-bold">
<span><?= resultTimeAgo($first['published_at'] ?? '') ?></span>
<span class="fs-4 lh-1 user-select-none">·</span>
<span><?= displayAuthor($first) ?></span>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-column gap-4">
                        <?php foreach ($thoiSu as $p): ?>
                            <div class="side-post border-bottom pb-4">
                                <span class="side-category fw-bold text-uppercase d-block mb-2">
                                    <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                </span>
                                <a href="index.php?page=post&id=<?= $p['post_id'] ?>" class="text-decoration-none text-dark d-block">
                                    <h4 class="post-title h5 fw-bold mb-2 clamp-2">
                                        <?= htmlspecialchars($p['title'] ?? '') ?>
                                    </h4>
                                    <p class="post-summary"><?= htmlspecialchars($p['summary'] ?? '') ?></p>
                                </a>
                                <div class="side-meta d-flex align-items-center gap-2 mt-3 text-uppercase text-muted fw-bold">
                                    <span><?= resultTimeAgo($p['published_at'] ?? '') ?></span>
                                    <span class="fs-4 lh-1 user-select-none">·</span>
                                    <span><?= displayAuthor($p) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="mb-5 pb-4">
        <div class="d-flex justify-content-between align-items-end section-title-border mb-4 pb-2">
            <h2 class="section-heading h3 fw-bold m-0">Kinh tế</h2>
            <a href="index.php?page=category_detail&slug=kinh-te" class="section-more text-uppercase fw-bold text-decoration-none tracking-widest small" style="color: #b90c17;">
                Xem thêm
            </a>
        </div>

        <div class="row g-4 g-lg-5">
            <?php if (!empty($kinhTe)):
                $first = array_shift($kinhTe); ?>
                <div class="col-12 col-lg-7">
                    <a href="index.php?page=post&id=<?= $first['post_id'] ?>" class="text-decoration-none text-dark d-block">
                        <img src="<?= ($first['thumbnail_url'] ?? '') ?>" class="w-100 post-main-img mb-3 shadow-sm" alt="Thumbnail">
                        <div class="d-flex align-items-center gap-3 my-2 text-muted small">
                            <span class="main-category fw-bold text-uppercase">
                                <?= htmlspecialchars($first['category_name'] ?? '') ?>
                            </span>
                        </div>
                        <h3 class="post-title h3 fw-bold mb-3">
                            <?= htmlspecialchars($first['title'] ?? '') ?>
                        </h3>
                        <p class="post-summary"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    </a>
                    <div class="post-meta d-flex align-items-center gap-2 mt-2 text-uppercase text-muted fw-bold">
                        <span><?= resultTimeAgo($first['published_at'] ?? '') ?></span>
                        <span class="fs-4 lh-1 user-select-none">·</span>
                        <span><?= displayAuthor($first) ?></span>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-column gap-4">
                        <?php foreach ($kinhTe as $p): ?>
                            <div class="side-post border-bottom pb-4">
                                <span class="side-category fw-bold text-uppercase d-block mb-2">
                                    <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                </span>
                                <a href="index.php?page=post&id=<?= $p['post_id'] ?>" class="text-decoration-none text-dark d-block">
                                    <h4 class="post-title h5 fw-bold mb-2 clamp-2">
                                        <?= htmlspecialchars($p['title'] ?? '') ?>
                                    </h4>
                                    <p class="post-summary"><?= htmlspecialchars($p['summary'] ?? '') ?></p>
                                </a>
                                <div class="side-meta d-flex align-items-center gap-2 mt-3 text-uppercase text-muted fw-bold">
                                    <span><?= resultTimeAgo($p['published_at'] ?? '') ?></span>
                                    <span class="fs-4 lh-1 user-select-none">·</span>
                                    <span><?= displayAuthor($p) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php include __DIR__ . '/../Partials/Client/Footer.php'; ?>
<script src="/../Web-Application/Public/Client/Js/Home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
