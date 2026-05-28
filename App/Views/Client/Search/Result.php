<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;

$keywordSafe = htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8');

$currentPaginationPage = max(1, (int)($_GET['p'] ?? 1));
$totalPosts = (int)($totalPosts ?? 0);
$totalPages = (int)($totalPages ?? 0);

$selectedCategories = $_GET['categories'] ?? [];

if (!is_array($selectedCategories)) {
    $selectedCategories = [$selectedCategories];
}

$selectedCategories = array_values(array_filter($selectedCategories, function ($item) {
    return $item !== '';
}));

$categories = $categories ?? [];
$categoryGroups = [];

foreach ($categories as $cat) {
    $parentId = $cat['parent_id'] ?? null;

    if (!$parentId) {
        continue;
    }

    if (!isset($categoryGroups[$parentId])) {
        $categoryGroups[$parentId] = [
            'id' => $cat['parent_id'],
            'name' => $cat['parent_name'] ?? '',
            'children' => []
        ];
    }

    if (!empty($cat['child_id'])) {
        $categoryGroups[$parentId]['children'][] = [
            'id' => $cat['child_id'],
            'name' => $cat['child_name'] ?? ''
        ];
    }
}

$selectedCategoryId = $selectedCategories[0] ?? '';
$selectedCategoryLabel = 'Danh mục';

foreach ($categoryGroups as $parent) {
    foreach ($parent['children'] as $child) {
        if ((string)$child['id'] === (string)$selectedCategoryId) {
            $selectedCategoryLabel = $parent['name'] . ' / ' . $child['name'];
        }
    }
}

function resultBuildQuery($extra = [])
{
    $query = $_GET;

    foreach ($extra as $key => $value) {
        $query[$key] = $value;
    }

    return http_build_query($query);
}

function resultTimeAgo($datetime)
{
    if (empty($datetime)) {
        return '';
    }

    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 3600) {
        return max(1, floor($diff / 60)) . ' phút trước';
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
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >
    <link rel="stylesheet" href="Public/Client/Css/SearchResult.css?v=31">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/Client_Global.css">
</head>

<body>

    <?php include __DIR__ . '/../../Partials/Client/Header.php'; ?>

    <main class="result-page">
        <section class="result-hero">
            <div class="result-container">
                <div class="result-hero-row">
                    <div>
                        <h1 class="result-title">
                            Kết quả tìm kiếm cho “<?= $keywordSafe ?>”
                        </h1>

                        <p class="result-subtitle">
                            Tìm thấy <span><?= $totalPosts ?></span> kết quả phù hợp với từ khóa của bạn.
                        </p>
                    </div>

                    <form class="result-search-box" method="GET" action="index.php">
                        <input type="hidden" name="page" value="search_result">

                        <div class="result-search-input-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input 
                                type="text" 
                                name="key" 
                                value="<?= $keywordSafe ?>" 
                                placeholder="Tìm kiếm tin tức..."
                            >
                        </div>

                        <button type="submit">Tìm</button>
                    </form>
                </div>

                <div class="result-divider"></div>
            </div>
        </section>

        <section class="result-content-section">
            <div class="result-container result-layout">
                <aside class="result-sidebar">
                    <form id="resultFilterForm" method="GET" action="index.php">
                        <input type="hidden" name="page" value="search_result">
                        <input type="hidden" name="key" value="<?= $keywordSafe ?>">

                        <?php $selectedTime = $_GET['time'] ?? 'newest'; ?>

                        <div class="result-filter-group">
                            <h3>Thời gian</h3>

                            <label class="result-radio">
                                <input 
                                    type="radio" 
                                    name="time" 
                                    value="newest" 
                                    <?php if ($selectedTime === 'newest') echo 'checked'; ?>
                                >
                                <span></span>
                                Mới nhất
                            </label>

                            <label class="result-radio">
                                <input 
                                    type="radio" 
                                    name="time" 
                                    value="24h" 
                                    <?php if ($selectedTime === '24h') echo 'checked'; ?>
                                >
                                <span></span>
                                Trong 24h qua
                            </label>

                            <label class="result-radio">
                                <input 
                                    type="radio" 
                                    name="time" 
                                    value="week" 
                                    <?php if ($selectedTime === 'week') echo 'checked'; ?>
                                >
                                <span></span>
                                Trong tuần qua
                            </label>

                            <label class="result-radio">
                                <input 
                                    type="radio" 
                                    name="time" 
                                    value="oldest" 
                                    <?php if ($selectedTime === 'oldest') echo 'checked'; ?>
                                >
                                <span></span>
                                Cũ nhất
                            </label>

                            <label class="result-radio">
                                <input 
                                    type="radio" 
                                    name="time" 
                                    value="custom" 
                                    <?php if ($selectedTime === 'custom') echo 'checked'; ?>
                                >
                                <span></span>
                                Tùy chọn
                            </label>

                            <div class="result-date-box">
                                <label>Từ ngày</label>
                                <input 
                                    type="date" 
                                    name="from_date"
                                    value="<?= htmlspecialchars($_GET['from_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                >
                            </div>

                            <div class="result-date-box">
                                <label>Đến ngày</label>
                                <input 
                                    type="date" 
                                    name="to_date"
                                    value="<?= htmlspecialchars($_GET['to_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                >
                            </div>
                        </div>

                        <div class="result-filter-group">
                            <h3>Danh mục</h3>

                            <div class="result-category-dropdown">
                                <input 
                                    type="hidden" 
                                    name="categories[]" 
                                    value="<?= htmlspecialchars($selectedCategoryId, ENT_QUOTES, 'UTF-8') ?>" 
                                    id="resultCategoryInput"
                                >

                                <button type="button" class="result-category-btn">
                                    <span id="resultCategoryLabel">
                                        <?= htmlspecialchars($selectedCategoryLabel, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>

                                <div class="result-category-menu">
                                    <button 
                                        type="button" 
                                        class="result-category-clear"
                                        data-category=""
                                        data-label="Danh mục"
                                    >
                                        Danh mục
                                    </button>

                                    <?php foreach ($categoryGroups as $parent): ?>
                                        <div class="result-category-parent">
                                            <button type="button" class="result-parent-btn">
                                                <?= htmlspecialchars($parent['name'], ENT_QUOTES, 'UTF-8') ?>
                                                <i class="fa-solid fa-chevron-right"></i>
                                            </button>

                                            <div class="result-category-submenu">
                                                <?php foreach ($parent['children'] as $child): ?>
                                                    <button 
                                                        type="button"
                                                        class="result-category-option <?= (string)$selectedCategoryId === (string)$child['id'] ? 'active' : '' ?>"
                                                        data-category="<?= htmlspecialchars($child['id'], ENT_QUOTES, 'UTF-8') ?>"
                                                        data-label="<?= htmlspecialchars($parent['name'] . ' / ' . $child['name'], ENT_QUOTES, 'UTF-8') ?>"
                                                    >
                                                        <?= htmlspecialchars($child['name'], ENT_QUOTES, 'UTF-8') ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="result-filter-group">
                            <h3>Tác giả</h3>

                            <div class="result-author-input">
                                <i class="fa-regular fa-user"></i>

                                <input 
                                    type="text" 
                                    name="author"
                                    value="<?= htmlspecialchars($_GET['author'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Nhập tên tác giả..."
                                >
                            </div>
                        </div>
                    </form>
                </aside>

                <div class="result-list-wrap">
                    <?php if (!empty($posts)): ?>
                        <div class="result-post-list">
                            <?php foreach ($posts as $post): ?>
                                <?php
                                $postId = htmlspecialchars($post['post_id'] ?? '', ENT_QUOTES, 'UTF-8');
                                $title = htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8');
                                $summary = htmlspecialchars($post['summary'] ?? '', ENT_QUOTES, 'UTF-8');

                                $thumbnail = !empty($post['thumbnail_url'])
                                    ? htmlspecialchars($post['thumbnail_url'], ENT_QUOTES, 'UTF-8')
                                    : 'Public/Client/Images/default-news.jpg';

                                $authorName = htmlspecialchars($post['author_name'] ?? 'Tòa soạn', ENT_QUOTES, 'UTF-8');
                                ?>

                                <article class="result-post-item">
                                    <a class="result-post-thumbnail" href="index.php?page=post&id=<?= $postId ?>">
                                        <img src="<?= $thumbnail ?>" alt="<?= $title ?>">
                                    </a>

                                    <div class="result-post-content">
                                        <div class="result-post-categories">
                                            <?php if (!empty($post['parent_category_name'])): ?>
                                                <span class="result-parent-category">
                                                    <?= htmlspecialchars($post['parent_category_name'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($post['child_category_name'])): ?>
                                                <span class="result-child-category">
                                                    <?= htmlspecialchars($post['child_category_name'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <a class="result-post-title" href="index.php?page=post&id=<?= $postId ?>">
                                            <?= $title ?>
                                        </a>

                                        <p class="result-post-summary">
                                            <?= $summary ?>
                                        </p>

                                        <div class="result-post-meta">
                                            <span><?= resultTimeAgo($post['created_at'] ?? '') ?></span>
                                            <span>·</span>
                                            <span><?= $authorName ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                       <?php if ((int)$totalPages > 1): ?>
    <?php
    $windowSize = 3;

    $startPage = max(1, $currentPaginationPage - 1);
    $endPage = min($totalPages, $startPage + $windowSize - 1);

    if (($endPage - $startPage + 1) < $windowSize) {
        $startPage = max(1, $endPage - $windowSize + 1);
    }
    ?>

    <nav class="result-pagination">
        <?php if ((int)$currentPaginationPage > 1): ?>
            <a href="index.php?<?= resultBuildQuery(['p' => $currentPaginationPage - 1]) ?>">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a
                href="index.php?<?= resultBuildQuery(['p' => $i]) ?>"
                class="<?= (int)$i === (int)$currentPaginationPage ? 'active' : '' ?>"
            >
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ((int)$currentPaginationPage < (int)$totalPages): ?>
            <a href="index.php?<?= resultBuildQuery(['p' => $currentPaginationPage + 1]) ?>">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
                    <?php else: ?>
                        <div class="result-empty">
                            <h3>Không tìm thấy kết quả</h3>
                            <p>Thử thay đổi từ khóa hoặc bộ lọc tìm kiếm.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Public/Client/Js/SearchResult.js?v=31"></script>

</body>

</html>