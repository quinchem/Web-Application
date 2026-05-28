<?php
/** @var array $categories */

$breadcrumbs = [
    [
        'label' => 'DASHBOARD',
        'url'   => '#'
    ],
    [
        'label' => 'THỐNG KÊ'
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <!-- PROFILE CSS -->

    <link
        rel="stylesheet"
        href="Public/Admin/Css/Pages/Profile.css"
    >

    <!-- DASHBOARD CSS -->

    <link
        rel="stylesheet"
        href="Public/Admin/Css/Pages/dashboard.css?v=<?= time() ?>"
    >

    <!-- CHART JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/chart.js">
    </script>

    <!-- JQUERY -->

    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>

    <!-- DASHBOARD JS -->

    <script
        src="Public/Admin/Js/Pages/dashboard.js?v=<?= time() ?>"
        defer>
    </script>

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->

    <?php
    require_once __DIR__ .
    '/../../Partials/Admin/Sidebar.php';
    ?>

    <!-- MAIN -->

    <main class="main-content">

        <!-- BREADCRUMB -->

        <?php
        require_once __DIR__ .
        '/../../Partials/Admin/Header.php';
        ?>

        <!-- CONTENT -->

        <section class="content-inner">

            <!-- PAGE HEADER -->

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

            <!-- FILTER -->

            <div class="filter-box">

                <input
                    type="date"
                    id="fromDate"
                    class="filter-input"
                >

                <input
                    type="date"
                    id="toDate"
                    class="filter-input"
                >

                <select
                    id="categoryFilter"
                    class="filter-select"
                >

                    <option value="">
                        Danh mục
                    </option>

                    <?php foreach(($categories ?? []) as $item): ?>

                        <option
                            value="<?= $item['category_id'] ?>"
                        >
                            <?= htmlspecialchars($item['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

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

            <!-- KPI -->

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

            <!-- ============================= -->
            <!-- ROW 1 -->
            <!-- ============================= -->

            <div class="dashboard-top-grid">

                <!-- TOP POSTS -->

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

                        <!-- AJAX RENDER -->

                    </div>

                </div>

                <!-- STATUS -->

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

            <!-- ============================= -->
            <!-- ROW 2 -->
            <!-- ============================= -->

            <div class="dashboard-bottom-grid">

                <div class="dashboard-card full-width">

                    <div class="card-header">

                        <h3>
                            Thống kê bài viết
                        </h3>

                    </div>

                    <div class="chart-wrapper">

                        <canvas id="postChart"></canvas>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->

            <?php
            require_once __DIR__ .
            '/../../Partials/Admin/Footer.php';
            ?>

        </section>

    </main>

</div>

</body>
</html>