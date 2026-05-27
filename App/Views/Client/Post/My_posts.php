<?php
$myPosts = $myPosts ?? [];
$myPostCurrentPage = $myPostCurrentPage ?? 1;
$myPostTotalPages = $myPostTotalPages ?? 0;

$myPostKeyword = $myPostKeyword ?? '';
$myPostCategory = $myPostCategory ?? '';
$myPostStatus = $myPostStatus ?? '';
$myPostDate = $myPostDate ?? '';

$myPostTotalAll = $myPostTotalAll ?? 0;
$myPostTotalApproved = $myPostTotalApproved ?? 0;
$myPostTotalPending = $myPostTotalPending ?? 0;
$myPostTotalDraft = $myPostTotalDraft ?? 0;

$myPostCategories = $myPostCategories ?? [];
$selectedCategoryLabel = 'Danh mục';

if (!empty($myPostCategory) && !empty($myPostCategories)) {
    foreach ($myPostCategories as $category) {
        $parentName = $category['parent_name'] ?? '';
        $childName = $category['child_name'] ?? '';

        if ($childName === $myPostCategory) {
            $selectedCategoryLabel = $parentName . ' / ' . $childName;
            break;
        }
    }
}

function myPostStatusText($status)
{
    return match ($status) {
        'approved' => 'Đã đăng',
        'pending' => 'Chờ duyệt',
        'rejected' => 'Từ chối',
        'draft' => 'Nháp',
        'hidden' => 'Ẩn',
        default => $status ?: 'Không rõ',
    };
}

function myPostStatusClass($status)
{
    return match ($status) {
        'approved' => 'is-approved',
        'pending' => 'is-pending',
        'rejected' => 'is-rejected',
        'draft' => 'is-draft',
        'hidden' => 'is-hidden',
        default => 'is-draft',
    };
}
?>

<link rel="stylesheet" href="Public/Client/Css/ClientProfile_MyPost.css?v=<?= time() ?>">

<div class="my-post-page">

    <div class="my-post-header">
        <div>
            <h1>Quản lý bài viết</h1>
            <p>Phân tích và lưu trữ nội dung biên tập</p>
        </div>

        <a href="index.php?page=create_post" class="my-post-create-btn">
            <i class="fa-solid fa-plus"></i>
            Đăng bài mới
        </a>
    </div>

    <div class="my-post-stats">
        <div class="my-post-stat-card is-total">
            <i class="fa-regular fa-newspaper"></i>
            <strong><?= $myPostTotalAll ?></strong>
            <span>Tổng số bài viết</span>
        </div>

        <div class="my-post-stat-card is-published">
            <i class="fa-regular fa-window-maximize"></i>
            <strong><?= $myPostTotalApproved ?></strong>
            <span>Bài đã xuất bản</span>
        </div>

        <div class="my-post-stat-card is-pending">
            <i class="fa-regular fa-eye-slash"></i>
            <strong><?= $myPostTotalPending ?></strong>
            <span>Bài chờ duyệt</span>
        </div>

        <div class="my-post-stat-card is-draft">
            <i class="fa-regular fa-clock"></i>
            <strong><?= $myPostTotalDraft ?></strong>
            <span>Bản nháp</span>
        </div>
    </div>

    <form class="my-post-filter js-my-post-filter" method="GET" action="index.php">
        <input type="hidden" name="page" value="client_profile">
        <input type="hidden" name="tab" value="my_posts">

        <div class="my-post-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="keyword" value="<?= htmlspecialchars($myPostKeyword) ?>"
                placeholder="Tìm kiếm">
        </div>

        <div class="my-post-category-dropdown">
            <input type="hidden" name="category" value="<?= htmlspecialchars($myPostCategory) ?>"
                id="myPostCategoryInput">

            <button type="button" class="my-post-category-btn">
                <span id="myPostCategoryLabel">
                    <?= htmlspecialchars($selectedCategoryLabel) ?>
                </span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="my-post-category-menu">
                <button type="button" class="my-post-category-clear" data-category="" data-label="Danh mục">
                    Danh mục
                </button>

                <?php
                $groupedCategories = [];

                foreach ($myPostCategories as $category) {
                    $parentName = $category['parent_name'] ?? '';
                    $childName = $category['child_name'] ?? '';

                    if ($parentName !== '' && $childName !== '') {
                        $groupedCategories[$parentName][] = $childName;
                    }
                }
                ?>

                <?php foreach ($groupedCategories as $parentName => $children): ?>
                    <div class="my-post-category-parent">
                        <button type="button" class="my-post-parent-btn">
                            <?= htmlspecialchars($parentName) ?>
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                        <div class="my-post-category-submenu">
                            <?php foreach ($children as $childName): ?>
                                <button type="button"
                                    class="my-post-category-option <?= $myPostCategory === $childName ? 'active' : '' ?>"
                                    data-category="<?= htmlspecialchars($childName) ?>"
                                    data-label="<?= htmlspecialchars($parentName . ' / ' . $childName) ?>">
                                    <?= htmlspecialchars($childName) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <select name="status">
            <option value="">Trạng thái</option>
            <option value="approved" <?= $myPostStatus === 'approved' ? 'selected' : '' ?>>Đã đăng</option>
            <option value="pending" <?= $myPostStatus === 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
            <option value="draft" <?= $myPostStatus === 'draft' ? 'selected' : '' ?>>Nháp</option>
            <option value="rejected" <?= $myPostStatus === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
            <option value="hidden" <?= $myPostStatus === 'hidden' ? 'selected' : '' ?>>Ẩn</option>
        </select>

        <input type="date" name="date" value="<?= htmlspecialchars($myPostDate) ?>">

        <button type="submit" class="my-post-filter-btn">
            <i class="fa-solid fa-filter"></i>
        </button>
    </form>

    <div class="my-post-table-wrap">
        <table class="my-post-table">
            <thead>
                <tr>
                    <th>Bài viết</th>
                    <th>Danh mục</th>
                    <th>Ngày đăng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($myPosts)): ?>
                    <?php foreach ($myPosts as $post): ?>
                        <?php
                        $postId = $post['post_id'] ?? '';
                        $title = $post['title'] ?? '';
                        $status = $post['status'] ?? '';
                        $createdAt = $post['created_at'] ?? '';
                        $parentCategory = $post['parent_category_name'] ?? '';
                        $childCategory = $post['category_name'] ?? '';
                        ?>

                        <tr>
                            <td class="my-post-title-cell">
                                <a href="index.php?page=post&id=<?= urlencode($postId) ?>" class="my-post-title">
                                    <?= htmlspecialchars($title) ?>
                                </a>

                                <p>
                                    Tác giả: <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['user_name'] ?? '') ?>
                                    · ID: <?= htmlspecialchars($postId) ?>
                                </p>
                            </td>

                            <td>
                                <div class="my-post-category-pill">
                                    <strong><?= htmlspecialchars($parentCategory ?: $childCategory) ?></strong>

                                    <?php if (!empty($parentCategory) && !empty($childCategory)): ?>
                                        <span><?= htmlspecialchars($childCategory) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="my-post-date">
                                <?= !empty($createdAt) ? date('d/m/Y', strtotime($createdAt)) : '' ?>
                            </td>

                            <td>
                                <span class="my-post-status <?= myPostStatusClass($status) ?>">
                                    <?= htmlspecialchars(myPostStatusText($status)) ?>
                                </span>
                            </td>

                            <td>
                                <div class="my-post-actions">
                                    <a href="index.php?page=post&id=<?= urlencode($postId) ?>" title="Xem">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <a href="index.php?page=edit_post&id=<?= urlencode($postId) ?>" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <a href="index.php?page=delete_post&id=<?= urlencode($postId) ?>" title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa bài viết này không?')">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="my-post-empty">
                            Bạn chưa đăng bài viết nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($myPostTotalPages > 1): ?>
            <div class="my-post-pagination">

                <?php if ($myPostCurrentPage > 1): ?>
                    <button type="button" class="js-my-post-page" data-page="<?= $myPostCurrentPage - 1 ?>">
                        ‹
                    </button>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $myPostTotalPages; $i++): ?>
                    <button type="button" class="js-my-post-page <?= $i == $myPostCurrentPage ? 'active' : '' ?>"
                        data-page="<?= $i ?>">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>

                <?php if ($myPostCurrentPage < $myPostTotalPages): ?>
                    <button type="button" class="js-my-post-page" data-page="<?= $myPostCurrentPage + 1 ?>">
                        ›
                    </button>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>

</div>