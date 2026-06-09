<?php
/** @var array $categories */
$breadcrumbs = [['label' => 'THỐNG KÊ']];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css" >
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Sidebar.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Header.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Footer.css">
    <link rel="stylesheet"href="Public/Admin/Css/Pages/dashboard.css?v=<?= time() ?>">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"> </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"> </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    <script src="Public/Admin/Js/Pages/dashboard.js?v=<?= time() ?>"defer></script>
    <script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>

    <style>
        .custom-modal-backdrop,
        #changePasswordModal,
        #editProfileModal {
            position: fixed !important;
            inset: 0 !important;
            z-index: 999999 !important;
        }
    </style>
</head>

</div> 
<?php require_once __DIR__ . '/../Profile/edit.php'; ?>
<?php require_once __DIR__ . '/../Profile/change_password.php'; ?>

<body>
<div class="admin-layout">

    <?php
    require_once __DIR__ . '/../../Partials/Admin/Sidebar.php';
    ?>

    <main class="main-content">
        <?php
        require_once __DIR__ . '/../../Partials/Admin/Header.php';
        ?>

        <section class="content-inner">
            <div class="page-header">
                <div>
                    <h1>
                        THỐNG KÊ TỔNG QUAN
                    </h1>

                    <p class="page-desc">
                        Theo dõi hoạt động hệ thống bài viết
                    </p>
                </div>
            </div>

            <div class="filter-box">
                <input type="text" id="fromDate" class="filter-input" placeholder="Từ ngày..." autocomplete="off">
                <input type="text" id="toDate"   class="filter-input" placeholder="Đến ngày..." autocomplete="off">
                
                <?php
                    // Build cây cha/con từ $categories
                    $catParents  = [];
                    $catChildren = [];
                    foreach (($categories ?? []) as $cat) {
                        if (empty($cat['parent_id'])) {
                            $catParents[$cat['category_id']] = $cat;
                        } else {
                            $catChildren[$cat['parent_id']][] = $cat;
                        }
                    }
                ?>
            <div class="dash-category-dropdown" id="dashCategoryDropdown">
                <button type="button" class="dash-category-trigger" id="dashCategoryTrigger">
                    <span id="dashCategoryLabel">Danh mục</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="dash-category-menu" id="dashCategoryMenu">
                    <div class="dash-cat-reset" data-value="" data-label="Danh mục">
                        Tất cả danh mục
                    </div>
                    <?php foreach ($catParents as $parentId => $parent): ?>
                        <div class="dash-cat-parent" data-parent-id="<?= $parentId ?>">
                            <div class="dash-cat-parent-label">
                                <span><?= htmlspecialchars($parent['name']) ?></span>
                                <?php if (!empty($catChildren[$parentId])): ?>
                                    <i class="fa-solid fa-chevron-right"></i>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($catChildren[$parentId])): ?>
                                <div class="dash-cat-children">
                                    <?php foreach ($catChildren[$parentId] as $child): ?>
                                        <div class="dash-cat-child"
                                            data-value="<?= htmlspecialchars($child['category_id']) ?>"
                                            data-label="<?= htmlspecialchars($parent['name'] . ' › ' . $child['name']) ?>">
                                            <?= htmlspecialchars($child['name']) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- hidden input để JS đọc giá trị -->
                <input type="hidden" id="categoryFilter" value="">
            </div>

                <input
                    type="text"
                    id="authorFilter"
                    class="filter-input"
                    placeholder="Tên tác giả..."
                >

                <button
                    id="filterBtn"
                    class="filter-btn"
                >

                    <i class="fa-solid fa-filter"></i>

                </button>
            </div>

            <div class="stat-grid">
                <div class="stat-card red">
                    <div class="stat-icon">
                        <i class="fa-regular fa-newspaper"></i>
                    </div>

                    <h2 id="totalPosts">
                        0
                    </h2>
                    <p>
                        TỔNG BÀI VIẾT
                    </p>
                </div>

                <div class="stat-card darkred">
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>

                    <h2 id="pendingPosts">
                        0
                    </h2>
                    <p>
                        BÀI CHỜ DUYỆT
                    </p>
                </div>

                <div class="stat-card yellow">
                    <div class="stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h2 id="totalAuthors">
                        0
                    </h2>
                    <p>
                        TỔNG NGƯỜI ĐỌC
                    </p>
                </div>

                <div class="stat-card blue">
                    <div class="stat-icon">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <h2 id="totalViews">
                        0
                    </h2>
                    <p>
                        TỔNG LƯỢT XEM
                    </p>
                </div>
            </div>

            <div class="dashboard-top-grid">

                <div class="dashboard-card top-posts-card">
                    <div class="card-header">
                        <h3>
                            Top bài viết nổi bật
                        </h3>
                    </div>

                    <div
                        class="top-posts-list"
                        id="topPostsBody"
                    >
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>
                            Trạng thái bài viết
                        </h3>
                    </div>

                    <div class="chart-wrapper small-chart">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-bottom-grid">
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h3>
                            Thống kê bài viết đã đăng theo thời gian
                        </h3>
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="postChart"></canvas>
                    </div>
                </div>
            </div>

            <?php
            require_once __DIR__ . '/../../Partials/Admin/Footer.php';
            ?>
        </section>
    </main>
</div>
</body>
</html>