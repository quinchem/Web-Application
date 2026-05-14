<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <title>Quản lý bài viết người đọc</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/Web-Application/Public/Admin/Css/PostIndex.css">
</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php require_once __DIR__ . '/../../Partials/Admin/Sidebar.php'; ?>

    <!-- MAIN -->
    <main class="main-content">

        <!-- BREADCRUMB -->
        <div class="topbar">
    <div class="breadcrumb">
        <a href="#">QUẢN LÝ BÀI VIẾT</a>
        <span>></span>
        <span class="active">BÀI VIẾT NGƯỜI ĐỌC</span>
    </div>
</div>

        <section class="content-inner">

            <!-- TITLE -->
            <h1>QUẢN LÝ BÀI VIẾT</h1>

            <!-- STATISTIC -->
            <div class="stat-grid">

                <div class="stat-card red">
                    <span>
                        <i class="fa-regular fa-newspaper"></i>
                    </span>

                    <h2><?= $totalPosts ?? 0 ?></h2>

                    <p>TỔNG SỐ BÀI VIẾT</p>
                </div>

                <div class="stat-card orange">
                    <span>
                        <i class="fa-regular fa-clock"></i>
                    </span>

                    <h2><?= $pendingPosts ?? 0 ?></h2>

                    <p>BÀI CHỜ DUYỆT</p>
                </div>

                <div class="stat-card darkred">
                    <span>
                        <i class="fa-regular fa-eye-slash"></i>
                    </span>

                    <h2><?= $hiddenPosts ?? 0 ?></h2>

                    <p>BÀI ĐANG ẨN</p>
                </div>

                <div class="stat-card yellow">
                    <span>
                        <i class="fa-solid fa-bolt"></i>
                    </span>

                    <h2><?= $trendingPosts ?? 0 ?></h2>

                    <p>BÀI TRENDING</p>
                </div>

            </div>

            <!-- FILTER -->
       <div class="filter-box">

    <div class="search-input">
        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            placeholder="Tìm kiếm tiêu đề, tác giả..."
        >
    </div>

    <button>
        Danh mục
        <i class="fa-solid fa-chevron-down"></i>
    </button>

    <button>
        Tác giả
        <i class="fa-solid fa-chevron-down"></i>
    </button>

    <button>
        Trạng thái
        <i class="fa-solid fa-chevron-down"></i>
    </button>

    <button>
        <i class="fa-regular fa-calendar"></i>
        Thời gian
    </button>

    <button class="filter-btn">
        <i class="fa-solid fa-filter"></i>
    </button>

</div>

               

            <!-- TABLE -->
            <div class="table-card">

                <table>

                    <thead>
                    <tr>
                        <th>BÀI VIẾT</th>
                        <th>DANH MỤC</th>
                        <th>TÁC GIẢ</th>
                        <th>TRẠNG THÁI</th>
                        <th>TƯƠNG TÁC</th>
                        <th>THAO TÁC</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (!empty($posts)): ?>

                        <?php foreach ($posts as $post): ?>

                            <tr>

                                <!-- TITLE -->
                                <td class="post-title">
                                    <?= htmlspecialchars($post->title) ?>
                                </td>

                                <!-- CATEGORY -->
                                <td>
                                    <div class="category-pill">

                                        <?= htmlspecialchars($post->parent_category_name ?: $post->category_name) ?>

                                        <small>
                                            <?= htmlspecialchars($post->category_name) ?>
                                        </small>

                                    </div>
                                </td>

                                <!-- AUTHOR -->
                                <td>

                                    <strong>
                                        <?= htmlspecialchars($post->author_name) ?>
                                    </strong>

                                    <small>
                                        <?= date('d/m/Y', strtotime($post->created_at)) ?>
                                        <br>
                                        09:00
                                    </small>

                                </td>

                                <!-- STATUS -->
                                <td>

                                    <?php
                                    $statusText = [
                                        'approved' => 'ĐÃ XUẤT BẢN',
                                        'pending' => 'CHỜ DUYỆT',
                                        'hidden' => 'ĐÃ ẨN',
                                        'draft' => 'BẢN NHÁP',
                                        'rejected' => 'TỪ CHỐI'
                                    ];
                                    ?>

                                    <span class="status <?= htmlspecialchars($post->status) ?>">

                                        <?= $statusText[$post->status] ?? 'KHÔNG RÕ' ?>

                                    </span>

                                </td>

                                <!-- INTERACTION -->
                                <td>

                                    <?php if ($post->status === 'approved'): ?>

                                        <strong>
                                            <?= $post->view_count ?? 0 ?>
                                        </strong>

                                        <small>Lượt xem</small>

                                    <?php else: ?>

                                        <span>Chưa công bố</span>

                                    <?php endif; ?>

                                </td>

                                <!-- ACTION -->
                                <td class="actions">

                                    <?php if ($post->status === 'pending'): ?>

                                        <a href="#" class="approve-btn">
                                            DUYỆT
                                            <br>
                                            BÀI
                                        </a>

                                    <?php else: ?>

                                        <button class="view-btn">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>

                                        <button class="delete-btn">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">
                                Không có dữ liệu bài viết.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

                <!-- PAGINATION -->
                <div class="pagination">

                    <span>
                        HIỂN THỊ 1 - 10 TRONG SỐ <?= $totalPosts ?? 0 ?> BÀI VIẾT
                    </span>

                    <div>

                        <button>‹</button>

                        <button class="current">1</button>

                        <button>2</button>

                        <button>3</button>

                        <button>...</button>

                        <button>129</button>

                        <button>›</button>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>

        </section>

    </main>

</div>

</body>
</html>