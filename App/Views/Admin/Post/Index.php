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
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Sidebar.css">
    <script src="Public/Admin/Js/Pages/ReviewPost.js?v=<?= time() ?>" defer></script>
    <script src="Public/Admin/Js/Pages/Post.js?v=<?= time() ?>" defer></script>
    <script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>
    <style>
    html, body { height: 100%; overflow: hidden; }
    .admin-layout { height: 100vh; overflow: hidden; }
    .sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; flex-shrink: 0; }
    .main-content { height: 100vh; overflow-y: auto; flex: 1; }
    .profile-wrapper { padding: 16px 18px; }
    .admin-profile { padding: 14px 16px; gap: 12px; border-radius: 14px; }
    .admin-profile img { width: 40px; height: 40px; }
    .profile-info strong { font-size: 14px; margin-bottom: 6px; }
    .profile-info p { font-size: 11px; gap: 7px; }
    .profile-info i { font-size: 13px; }

    /* ✅ FIX: Modal thoát khỏi stacking context của main-content */
    .custom-modal-backdrop,
    #changePasswordModal,
    #editProfileModal {
        position: fixed !important;
        inset: 0 !important;
        z-index: 999999 !important;
    }
</style>
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

            <form class="filter-box" method="GET" action="Admin_index.php">
                <input type="hidden" name="page" value="admin_user_posts">

                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="keyword"
                        placeholder="Tìm kiếm tiêu đề, tác giả..."
                        value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
                </div>

                <!-- ── Category dropdown — load động từ $categories ── -->
                <?php
                $parents  = [];
                $children = [];
                foreach (($categories ?? []) as $cat) {
                    if (empty($cat['parent_id'])) {
                        $parents[$cat['category_id']] = $cat;
                    } else {
                        $children[$cat['parent_id']][] = $cat;
                    }
                }

                $selectedCatId = $filters['category_id'] ?? '';
                $selectedLabel = 'Danh mục';
                foreach (($categories ?? []) as $cat) {
                    if ((string)$cat['category_id'] === (string)$selectedCatId) {
                        if (!empty($cat['parent_id']) && isset($parents[$cat['parent_id']])) {
                            $selectedLabel = $parents[$cat['parent_id']]['name'] . ' › ' . $cat['name'];
                        } else {
                            $selectedLabel = $cat['name'];
                        }
                        break;
                    }
                }
                ?>
                <div class="category-dropdown" id="categoryDropdown">
                    <button type="button" class="category-trigger" id="categoryTrigger">
                        <span id="categoryLabel"><?= htmlspecialchars($selectedLabel) ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>

                    <div class="category-menu" id="categoryMenu" style="display:none">
                        <?php foreach ($parents as $parentId => $parent): ?>
                        <div class="cat-parent" data-id="<?= htmlspecialchars($parentId) ?>">
                            <div class="cat-parent-label">
                                <span><?= htmlspecialchars($parent['name']) ?></span>
                                <?php if (!empty($children[$parentId])): ?>
                                    <i class="fa-solid fa-chevron-right"></i>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($children[$parentId])): ?>
                            <div class="cat-children" style="display:none">
                                <?php foreach ($children[$parentId] as $child): ?>
                                <div class="cat-child"
                                     data-value="<?= htmlspecialchars($child['category_id']) ?>"
                                     data-label="<?= htmlspecialchars($parent['name']) ?> › <?= htmlspecialchars($child['name']) ?>">
                                    <?= htmlspecialchars($child['name']) ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>

                        <div class="cat-reset" id="catReset">Tất cả danh mục</div>
                    </div>

                    <input type="hidden" name="category_id" id="categoryValue"
                        value="<?= htmlspecialchars($selectedCatId) ?>">
                </div>

                <select name="author_id" class="filter-select">
                    <option value="">Tác giả</option>
                    <?php foreach (($authors ?? []) as $author): ?>
                        <option value="<?= htmlspecialchars($author['user_id']) ?>"
                            <?= ($filters['author_id'] ?? '') == $author['user_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($author['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="status" class="filter-select">
                    <option value="">Trạng thái</option>
                    <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Đã xuất bản</option>
                    <option value="pending"  <?= ($filters['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="hidden"   <?= ($filters['status'] ?? '') === 'hidden'   ? 'selected' : '' ?>>Đã ẩn</option>
                    <option value="draft"    <?= ($filters['status'] ?? '') === 'draft'    ? 'selected' : '' ?>>Bản nháp</option>
                    <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                </select>

                <div class="date-input-wrapper filter-select" style="display:flex;align-items:center;gap:8px;padding:0 14px;">
                    <i class="fa-regular fa-calendar" style="color:#9fb0bc;font-size:13px;"></i>
                    <input type="date" name="date"
                        style="border:none;outline:none;background:transparent;font-size:13px;font-weight:800;color:#07344a;font-family:'Barlow',sans-serif;width:100%;"
                        value="<?= htmlspecialchars($filters['date'] ?? '') ?>">
                </div>

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
                            <td class="post-title"><?= htmlspecialchars($post->title) ?></td>
                            <td>
                                <div class="category-pill">
                                    <?= htmlspecialchars($post->parent_category_name ?: $post->category_name) ?>
                                    <small><?= htmlspecialchars($post->category_name) ?></small>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($post->author_name) ?></strong>
                                <small><?= date('d/m/Y', strtotime($post->created_at)) ?><br>09:00</small>
                            </td>
                            <td>
                                <?php
                                $statusMap = [
                                    'approved' => ['label' => 'ĐÃ XUẤT BẢN', 'class' => 'approved'],
                                    'pending'  => ['label' => 'CHỜ DUYỆT',   'class' => 'pending'],
                                    'hidden'   => ['label' => 'ĐÃ ẨN',        'class' => 'hidden'],
                                    'draft'    => ['label' => 'BẢN NHÁP',    'class' => 'draft'],
                                    'rejected' => ['label' => 'TỪ CHỐI',     'class' => 'rejected'],
                                ];
                                $s = $statusMap[$post->status] ?? ['label' => 'KHÔNG RÕ', 'class' => 'draft'];
                                ?>
                                <span class="status <?= $s['class'] ?>"><?= $s['label'] ?></span>
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
                                        <button class="approve-btn" type="button"
                                            onclick="openReviewModal('<?= htmlspecialchars($post->post_id) ?>','<?= htmlspecialchars(addslashes($post->title)) ?>')">
                                            DUYỆT<br>BÀI
                                        </button>

                                    <?php elseif ($status === 'hidden'): ?>
                                        <a href="Admin_index.php?page=unhide_post&id=<?= htmlspecialchars($post->post_id) ?>&from=admin_user_posts"
                                           class="view-btn" title="Bỏ ẩn"
                                           onclick="return confirm('Bạn muốn hiện lại bài viết này?')">
                                            <i class="fa-regular fa-eye-slash"></i>
                                        </a>
                                        <button class="delete-btn" type="button">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>

                                    <?php else: ?>
                                        <a href="Admin_index.php?page=hide_post&id=<?= htmlspecialchars($post->post_id) ?>&from=admin_user_posts"
                                           class="view-btn" title="Ẩn bài"
                                           onclick="return confirm('Bạn muốn ẩn bài viết này?')">
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
                        <tr><td colspan="6" style="text-align:center;padding:40px;color:#aaa;">Không có dữ liệu bài viết.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php
                $currentPage   = max(1, (int)($currentPage ?? 1));
                $perPage       = max(1, (int)($perPage ?? 10));
                $totalPages    = max(1, (int)($totalPages ?? 1));
                $totalForPages = (int)($totalForPages ?? 0);
                ?>
                <div class="pagination d-flex justify-content-between align-items-center">
                    <span>
                        HIỂN THỊ <?= min(($currentPage-1)*$perPage+1, $totalForPages) ?>
                        - <?= min($currentPage*$perPage, $totalForPages) ?>
                        TRONG SỐ <?= (int)($totalPosts ?? 0) ?> BÀI VIẾT
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" onclick="goToPage(<?= $currentPage-1 ?>)"
                            <?= $currentPage <= 1 ? 'disabled' : '' ?>>‹</button>

                        <button type="button" class="<?= $currentPage == 1 ? 'current' : '' ?>"
                            onclick="goToPage(1)">1</button>

                        <?php if ($totalPages > 1): ?>
                            <?php if ($currentPage > 3): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>
                            <?php for ($i = max(2, $currentPage-1); $i <= min($totalPages-1, $currentPage+1); $i++): ?>
                                <button type="button" class="<?= $currentPage == $i ? 'current' : '' ?>"
                                    onclick="goToPage(<?= $i ?>)"><?= $i ?></button>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages - 2): ?>
                                <button type="button" disabled>...</button>
                            <?php endif; ?>
                            <button type="button" class="<?= $currentPage == $totalPages ? 'current' : '' ?>"
                                onclick="goToPage(<?= $totalPages ?>)"><?= $totalPages ?></button>
                        <?php endif; ?>

                        <button type="button" onclick="goToPage(<?= $currentPage+1 ?>)"
                            <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>›</button>
                    </div>
                </div>
            </div>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>
        </section>
    </main>
</div>

<?php require_once __DIR__ . '/Review.php'; ?>

// THÀNH:
<?php require_once __DIR__ . '/../Profile/edit.php'; ?>
<?php require_once __DIR__ . '/../Profile/change_password.php'; ?>

</body>
</html>