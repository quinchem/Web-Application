<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý bài viết người đọc</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="Public/Admin/Css/Pages/Post.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/ReviewPost.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">
<script src="Public/Admin/Js/Pages/ReviewPost.js?v=<?= time() ?>" defer></script>
<script src="Public/Admin/Js/Pages/Post.js?v=<?= time() ?>" defer></script>
<script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>
    <style>
        /* Sidebar cố định, chỉ main-content scroll */
        html, body { height: 100%; overflow: hidden; }

        .admin-layout { height: 100vh; overflow: hidden; }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .main-content {
            height: 100vh;
            overflow-y: auto;
            flex: 1;
        }

        /* Thu nhỏ khung profile phía dưới sidebar */
        .profile-wrapper {
            padding: 16px 18px;
        }

        .admin-profile {
            padding: 14px 16px;
            gap: 12px;
            border-radius: 14px;
        }

        .admin-profile img {
            width: 40px;
            height: 40px;
        }

        .profile-info strong {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .profile-info p {
            font-size: 11px;
            gap: 7px;
        }

        .profile-info i {
            font-size: 13px;
        }
    </style>

    <script src="Public/Admin/Js/Pages/ReviewPost.js" defer></script>
    <script src="Public/Admin/Js/Pages/PostIndex.js" defer></script>
    <script src="Public/Admin/Js/Pages/Profile.js" defer></script>
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
<div class="category-dropdown" id="categoryDropdown">
   <button type="button" class="category-trigger" id="categoryTrigger">
    <span id="categoryLabel">Danh mục</span>
    <i class="fa-solid fa-chevron-down"></i>
</button>

    <div class="category-menu" id="categoryMenu" style="display:none">
        <div class="cat-parent" data-id="1">
            <div class="cat-parent-label">
                <span>Thời sự</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="cat-children" style="display:none">
                <div class="cat-child" data-value="11" data-label="Thời sự › Chính trị">Chính trị</div>
                <div class="cat-child" data-value="12" data-label="Thời sự › Xã hội">Xã hội</div>
                <div class="cat-child" data-value="13" data-label="Thời sự › Quân sự">Quân sự</div>
            </div>
        </div>
        <div class="cat-parent" data-id="2">
            <div class="cat-parent-label">
                <span>Kinh tế</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="cat-children" style="display:none">
                <div class="cat-child" data-value="21" data-label="Kinh tế › Thị trường">Thị trường</div>
                <div class="cat-child" data-value="22" data-label="Kinh tế › Ngân hàng">Ngân hàng</div>
                <div class="cat-child" data-value="23" data-label="Kinh tế › Chứng khoán">Chứng khoán</div>
                <div class="cat-child" data-value="24" data-label="Kinh tế › Doanh nghiệp">Doanh nghiệp</div>
            </div>
        </div>
        <div class="cat-parent" data-id="3">
            <div class="cat-parent-label">
                <span>Tiện ích</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="cat-children" style="display:none">
                <div class="cat-child" data-value="31" data-label="Tiện ích › Giá vàng">Giá vàng</div>
                <div class="cat-child" data-value="32" data-label="Tiện ích › Giá xăng">Giá xăng</div>
                <div class="cat-child" data-value="33" data-label="Tiện ích › Tỷ giá">Tỷ giá</div>
            </div>
        </div>
        <div class="cat-reset" id="catReset">Tất cả danh mục</div>
    </div>

    <input type="hidden" name="category_id" id="categoryValue"
        value="<?= htmlspecialchars($filters['category_id'] ?? '') ?>">
</div>

    
                


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
                    <option value="approved"  <?= ($filters['status'] ?? '') === 'approved'  ? 'selected' : '' ?>>Đã xuất bản</option>
                    <option value="pending"   <?= ($filters['status'] ?? '') === 'pending'   ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="hidden"    <?= ($filters['status'] ?? '') === 'hidden'    ? 'selected' : '' ?>>Đã ẩn</option>
                    <option value="draft"     <?= ($filters['status'] ?? '') === 'draft'     ? 'selected' : '' ?>>Bản nháp</option>
                    <option value="rejected"  <?= ($filters['status'] ?? '') === 'rejected'  ? 'selected' : '' ?>>Từ chối</option>
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
                                        <small><?= htmlspecialchars($post->category_name) ?></small>
                                    </div>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($post->author_name) ?></strong>
                                    <small>
                                        <?= date('d/m/Y', strtotime($post->created_at)) ?><br>09:00
                                    </small>
                                </td>

                                <td>
                                    <?php
                                    $statusText = [
                                        'approved' => 'ĐÃ XUẤT BẢN',
                                        'pending'  => 'CHỜ DUYỆT',
                                        'hidden'   => 'ĐÃ ẨN',
                                        'draft'    => 'BẢN NHÁP',
                                        'rejected' => 'TỪ CHỐI',
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
                                            <button
                                                class="approve-btn"
                                                type="button"
                                                onclick="openReviewModal(
                                                    '<?= htmlspecialchars($post->post_id) ?>',
                                                    '<?= htmlspecialchars(addslashes($post->title)) ?>'
                                                )"
                                            >
                                                DUYỆT<br>BÀI
                                            </button>

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
                            <td colspan="6">Không có dữ liệu bài viết.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php
                $currentPage   = max(1, (int)$currentPage);
                $perPage       = max(1, (int)$perPage);
                $totalPages    = max(1, (int)$totalPages);
                $totalForPages = (int)$totalForPages;
                ?>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <span>
                        HIỂN THỊ <?= min(($currentPage - 1) * $perPage + 1, $totalForPages) ?>
                        -
                        <?= min($currentPage * $perPage, $totalForPages) ?>
                        TRONG SỐ <?= (int)($totalPosts ?? 0) ?> BÀI VIẾT
                    </span>

                    <div class="d-flex gap-2">
                        <button type="button" onclick="goToPage(<?= $currentPage - 1 ?>)"
                            <?= $currentPage <= 1 ? 'disabled' : '' ?>>‹</button>

                        <button type="button" class="<?= $currentPage == 1 ? 'current' : '' ?>"
                            onclick="goToPage(1)">1</button>

                        <?php if ($totalPages > 1): ?>
                            <?php if ($currentPage > 3): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>

                            <?php for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++): ?>
                                <button type="button" class="<?= $currentPage == $i ? 'current' : '' ?>"
                                    onclick="goToPage(<?= $i ?>)"><?= $i ?></button>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages - 2): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>

                            <button type="button" class="<?= $currentPage == $totalPages ? 'current' : '' ?>"
                                onclick="goToPage(<?= $totalPages ?>)"><?= $totalPages ?></button>
                        <?php endif; ?>

                        <button type="button" onclick="goToPage(<?= $currentPage + 1 ?>)"
                            <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>›</button>
                    </div>
                </div>

            </div>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>

        </section>
    </main>
</div>

<?php require_once __DIR__ . '/Review.php'; ?>

</body>
</html>