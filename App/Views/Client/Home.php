<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .hero-section {
            height: 500px;
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
        }
        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1s ease;
        }
        .hero-section:hover .hero-img {
            transform: scale(1.05);
        }
        .hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, #003049, transparent);
        }
        .hero-content {
            position: absolute;
            inset: 0 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 850px;
        }
        .read-more-btn {
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            width: fit-content;
            padding-bottom: 0.25rem;
            transition: border-color 0.3s ease;
        }
        .read-more-btn:hover {
            border-color: #fff;
        }
        .section-title-border {
            border-bottom: 2px solid #003049;
        }
        .post-main-img {
            height: 400px;
            object-fit: cover;
            border-radius: 1rem;
            transition: opacity 0.3s ease;
        }
        .post-main-img:hover {
            opacity: 0.9;
        }
        .border-bottom:last-child {
    border-bottom: 0 !important;
}
        /* Custom Line Clamp cho Bootstrap để giới hạn số dòng chữ hiển thị */
        .clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        body { font-family: 'Montserrat', sans-serif; }
        .font-serif { font-family: 'Newsreader', serif; }
    </style>
</head>

<?php
/**
 * Home.php - Trang chủ (Bản Bootstrap 5)
 */
include __DIR__ . '/../Partials/Client/Header.php';
?>

<main class="container bg-white my-5 " style="width: 1140px; min-height: 100vh;">

    <?php if (isset($heroPost) && $heroPost): ?>
        <section class="hero-section mb-5 shadow">
            <a href="index.php?page=post&id=<?= $heroPost['post_id'] ?>" class="text-decoration-none">
                <img src="<?= $heroPost['thumbnail_url'] ?? '' ?>" class="hero-img" alt="Hero Thumbnail">
                <div class="hero-gradient"></div>
                <div class="hero-content text-white">
                    <div>
                        <span class="bg-danger text-white px-3 py-2 mb-4 d-inline-block fw-bold text-uppercase tracking-wider" style="font-size: 11px; font-family: sans-serif; background-color: #b90c17 !important;">
                            Tiêu điểm tuần qua
                        </span>
                    </div>
                    
                    <h1 class="display-6 fw-bold mb-4 style-serif" style="max-width: 750px; font-family: serif; line-height: 1.2;">
                        <?= htmlspecialchars($heroPost['title'] ?? '') ?>
                    </h1>
                    
                    <p class="text-white-50 fs-5 fw-light mb-4 clamp-4" style="max-width: 650px; line-height: 1.6;">
                        <?= htmlspecialchars($heroPost['summary'] ?? '') ?>
                    </p>
                    
                    <div class="read-more-btn d-flex align-items-center gap-2 text-white text-sm fw-medium cursor-pointer">
                        <span>Đọc toàn bộ bài viết</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </div>
            </a>
        </section>
    <?php endif; ?>


    <section class="mb-5 pb-4">
        <div class="d-flex justify-content-between align-items-end section-title-border mb-4 pb-2">
            <h2 class="h3 fw-bold m-0" style="color: #003049; font-family: 'Barlow', sans-serif;">Thời sự</h2>
            <a href="?page=category&name=Thời sự" class="text-uppercase fw-bold text-decoration-none tracking-widest text-danger small" style="color: #b90c17 !important; transition: opacity 0.2s;">
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
                            <span class="fw-bold text-uppercase" style="font-size: 14px; color: #b90c17; font-family: 'Barlow', sans-serif;">
                                <?= htmlspecialchars($first['category_name'] ?? '') ?>
                            </span>
                        </div>
                <h3 class="h3 fw-bold mb-3" style="font-family: serif; transition: color 0.2s;" onmouseover="this.style.color='#b90c17'" onmouseout="this.style.color=''">
                            <?= htmlspecialchars($first['title'] ?? '') ?>
                        </h3>
                        <p class="text-secondary fw-light" style="text-align: justify;"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    </a>
                    <div class="d-flex align-items-center gap-2 mt-2 text-uppercase text-muted fw-bold" style="font-size: 12px;">
                        <span><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                        <span class="fs-4 lh-1 user-select-none">·</span>
                        <span><?= htmlspecialchars($first['author_name'] ?? '') ?></span>
                    </div>
                </div>
                
                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-column gap-4">
                        <?php foreach ($thoiSu as $p): ?>
                            <div class="border-bottom pb-4" style="border-color: #e5e7eb !important; last-child:border-0;">
                                <span class="fw-bold text-uppercase d-block mb-2" style="font-size: 12px; color: #b90c17; font-family: 'Barlow', sans-serif;">
                                    <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                </span>
                                <a href="index.php?page=post&id=<?= $p['post_id'] ?>" class="text-decoration-none text-dark d-block">
                                    <h4 class="h5 fw-bold mb-2 clamp-2" style="font-family: serif; transition: color 0.2s;" onmouseover="this.style.color='#b90c17'" onmouseout="this.style.color=''">
                                        <?= htmlspecialchars($p['title'] ?? '') ?>
                                    </h4>
                                    <p class="text-secondary fw-light small m-0 clamp-3"><?= htmlspecialchars($p['summary'] ?? '') ?></p>
                                </a>
                                <div class="d-flex align-items-center gap-2 mt-3 text-uppercase text-muted fw-bold" style="font-size: 11px;">
                                    <span><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                                    <span class="fs-4 lh-1 user-select-none">·</span>
                                    <span><?= htmlspecialchars($p['author_name'] ?? '') ?></span>
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
            <h2 class="h3 fw-bold m-0" style="color: #003049; font-family: 'Barlow', sans-serif;">Kinh tế</h2>
            <a href="?page=category&name=Kinh tế" class="text-uppercase fw-bold text-decoration-none tracking-widest text-danger small" style="color: #b90c17 !important; transition: opacity 0.2s;">
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
                            <span class="fw-bold text-uppercase" style="font-size: 14px; color: #b90c17; font-family: 'Barlow', sans-serif;">
                                <?= htmlspecialchars($first['category_name'] ?? '') ?>
                            </span>
                        </div>
                        <h3 class="h3 fw-bold mb-3" style="font-family: serif; transition: color 0.2s;" onmouseover="this.style.color='#b90c17'" onmouseout="this.style.color=''">
                            <?= htmlspecialchars($first['title'] ?? '') ?>
                        </h3>
                        <p class="text-secondary fw-light" style="text-align: justify;"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    </a>
                    <div class="d-flex align-items-center gap-2 mt-2 text-uppercase text-muted fw-bold" style="font-size: 12px;">
                        <span><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                        <span class="fs-4 lh-1 user-select-none">·</span>
                        <span><?= htmlspecialchars($first['author_name'] ?? '') ?></span>
                    </div>
                </div>
                
                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-column gap-4">
                        <?php foreach ($kinhTe as $p): ?>
                            <div class="border-bottom pb-4" style="border-color: #e5e7eb !important; last-child:border-0;">
                                <span class="fw-bold text-uppercase d-block mb-2" style="font-size: 12px; color: #b90c17; font-family: 'Barlow', sans-serif;">
                                    <?= htmlspecialchars($p['category_name'] ?? '') ?>
                                </span>
                                <a href="index.php?page=post&id=<?= $p['post_id'] ?>" class="text-decoration-none text-dark d-block">
                                    <h4 class="h5 fw-bold mb-2 clamp-2" style="font-family: serif; transition: color 0.2s;" onmouseover="this.style.color='#b90c17'" onmouseout="this.style.color=''">
                                        <?= htmlspecialchars($p['title'] ?? '') ?>
                                    </h4>
                                    <p class="text-secondary fw-light small m-0 clamp-3"><?= htmlspecialchars($p['summary'] ?? '') ?></p>
                                </a>
                                <div class="d-flex align-items-center gap-2 mt-3 text-uppercase text-muted fw-bold" style="font-size: 11px;">
                                    <span><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                                    <span class="fs-4 lh-1 user-select-none">·</span>
                                    <span><?= htmlspecialchars($p['author_name'] ?? '') ?></span>
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
</html>