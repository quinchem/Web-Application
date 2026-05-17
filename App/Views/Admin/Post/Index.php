<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <title>Quản lý bài viết người đọc</title>

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="/Web-Application/Public/Admin/Css/PostIndex.css">
</head>

<body>

<div class="admin-layout">

    <?php require_once __DIR__ . '/../../Partials/Admin/Sidebar.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div class="breadcrumb">
                <a href="#">QUẢN LÝ BÀI VIẾT</a>
                <span>></span>
                <span class="active">BÀI VIẾT NGƯỜI ĐỌC</span>
            </div>
        </div>

        <section class="content-inner">

            <h1>QUẢN LÝ BÀI VIẾT</h1>

            <div class="stat-grid">

                <div class="stat-card red">
                    <span><i class="fa-regular fa-newspaper"></i></span>
                    <h2><?= $totalPosts ?? 0 ?></h2>
                    <p>TỔNG SỐ BÀI VIẾT</p>
                </div>

                <div class="stat-card orange">
                    <span><i class="fa-regular fa-clock"></i></span>
                    <h2><?= $pendingPosts ?? 0 ?></h2>
                    <p>BÀI CHỜ DUYỆT</p>
                </div>

                <div class="stat-card darkred">
                    <span><i class="fa-regular fa-eye-slash"></i></span>
                    <h2><?= $hiddenPosts ?? 0 ?></h2>
                    <p>BÀI ĐANG ẨN</p>
                </div>

                <div class="stat-card yellow">
                    <span><i class="fa-solid fa-bolt"></i></span>
                    <h2><?= $trendingPosts ?? 0 ?></h2>
                    <p>BÀI TRENDING</p>
                </div>

            </div>

            <form class="filter-box" method="GET" action="Index.php">

                <input type="hidden" name="page" value="admin_user_posts">

                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="keyword"
                        placeholder="Tìm kiếm tiêu đề, tác giả..."
                        value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>"
                    >
                </div>

                <select name="category_id" class="filter-select">
                    <option value="">Danh mục</option>

                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= htmlspecialchars($category['category_id']) ?>"
                                <?= ($filters['category_id'] ?? '') == $category['category_id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="author_id" class="filter-select">
                    <option value="">Tác giả</option>

                    <?php if (!empty($authors)): ?>
                        <?php foreach ($authors as $author): ?>
                            <option
                                value="<?= htmlspecialchars($author['user_id']) ?>"
                                <?= ($filters['author_id'] ?? '') == $author['user_id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($author['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="status" class="filter-select">
                    <option value="">Trạng thái</option>
                    <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Đã xuất bản</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="hidden" <?= ($filters['status'] ?? '') === 'hidden' ? 'selected' : '' ?>>Đã ẩn</option>
                    <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Bản nháp</option>
                    <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                </select>

                <input
                    type="date"
                    name="date"
                    class="filter-date"
                    value="<?= htmlspecialchars($filters['date'] ?? '') ?>"
                >

                <button type="submit" class="filter-btn">
                    <i class="fa-solid fa-filter"></i>
                </button>

            </form>

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

                                <td class="post-title">
                                    <?= htmlspecialchars($post->title) ?>
                                </td>

                                <td>
                                    <div class="category-pill">
                                        <?= htmlspecialchars($post->parent_category_name ?: $post->category_name) ?>

                                        <small>
                                            <?= htmlspecialchars($post->category_name) ?>
                                        </small>
                                    </div>
                                </td>

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

                                <td>
                                    <?php if ($post->status === 'approved'): ?>
                                        <strong><?= $post->view_count ?? 0 ?></strong>
                                        <small>Lượt xem</small>
                                    <?php else: ?>
                                        <span>Chưa công bố</span>
                                    <?php endif; ?>
                                </td>

                                <td>
    <div class="actions">

        <?php $status = strtolower(preg_replace('/\s+/', '', $post->status)); ?>

        <?php if ($status === 'pending'): ?>

            <a href="Index.php?page=review_post&id=<?= htmlspecialchars($post->post_id) ?>" class="approve-btn">
                DUYỆT<br>BÀI
            </a>

        <?php elseif ($status !== 'hidden'): ?>

            <a 
                href="Index.php?page=hide_post&id=<?= htmlspecialchars($post->post_id) ?>"
                class="view-btn"
                onclick="return confirm('Bạn muốn ẩn bài viết này?')"
            >
                <i class="fa-regular fa-eye"></i>
            </a>

            <button class="delete-btn" type="button">
                <i class="fa-regular fa-trash-can"></i>
            </button>

        <?php endif; ?>

    </div>
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

                <div class="pagination">

                   <span>
    HIỂN THỊ <?= min(($currentPage - 1) * $perPage + 1, $totalForPages) ?> - <?= min($currentPage * $perPage, $totalForPages) ?> TRONG SỐ <?= $totalPosts ?? 0 ?> BÀI VIẾT
</span>

                    <div>
                        <button type="button" onclick="goToPage(<?= $currentPage - 1 ?>)" <?= $currentPage <= 1 ? 'disabled' : '' ?>>‹</button>

                        <button type="button" class="<?= $currentPage == 1 ? 'current' : '' ?>" onclick="goToPage(1)">1</button>

                        <?php if ($totalPages > 1): ?>

                            <?php if ($currentPage > 3): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>

                            <?php for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++): ?>
                                <button type="button" class="<?= $currentPage == $i ? 'current' : '' ?>" onclick="goToPage(<?= $i ?>)"><?= $i ?></button>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages - 2): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>

                            <button type="button" class="<?= $currentPage == $totalPages ? 'current' : '' ?>" onclick="goToPage(<?= $totalPages ?>)"><?= $totalPages ?></button>

                        <?php endif; ?>

                        <button type="button" onclick="goToPage(<?= $currentPage + 1 ?>)" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>›</button>
                    </div>

                </div>

            </div><!-- đóng table-card -->

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>

        </section>

    </main>

</div><!-- đóng admin-layout -->

<script>
function goToPage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('p', page);
    window.location.href = url.toString();
}
</script>

</body>
</html>

<script>
function goToPage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('p', page);
    window.location.href = url.toString();
}
</script>

                </div>

            </div>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>

        </section>

    </main>

</div>

</body>
</html>