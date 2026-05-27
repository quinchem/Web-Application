<?php
$savedPosts = $savedPosts ?? [];
$savedCurrentPage = $savedCurrentPage ?? 1;
$savedTotalPages = $savedTotalPages ?? 0;
?>
<!-- savedCurrentPage = <?= $savedCurrentPage ?> | offset = <?= $savedOffset ?> -->

<link rel="stylesheet" href="/../Web-Application/Public/Client/Css/ClientProfile_SavedPost.css">

<div class="card shadow-sm border-0 p-4 profile-saved-card">
    <div class="saved-posts-container">

        <h1 class="saved-page-title">Danh sách bài viết đã lưu</h1>

        <?php if (!empty($savedPosts)): ?>

            <div class="saved-post-list">

                <?php foreach ($savedPosts as $post): ?>

                    <?php
                    $thumbnail = !empty($post['thumbnail_URL'])
                        ? $post['thumbnail_URL']
                        : 'Public/Images/default-post.jpg';

                    $summary = !empty($post['summary'])
                        ? $post['summary']
                        : mb_substr(strip_tags($post['content']), 0, 150) . '...';

                    $postUrl = 'index.php?page=post&id=' . urlencode($post['post_id']);
                    ?>

                    <div class="saved-post-item">

                        <a href="<?= $postUrl ?>" class="saved-post-thumbnail">
                            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        </a>

                        <div class="saved-post-content">

                            <div class="saved-post-categories">
                                <?php if (!empty($post['parent_category_name'])): ?>
                                    <span class="saved-parent-category">
                                        <?= htmlspecialchars($post['parent_category_name']) ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($post['category_name'])): ?>
                                    <span class="saved-child-category">
                                        <?= htmlspecialchars($post['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <a href="<?= $postUrl ?>" class="saved-post-title">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>

                            <p class="saved-post-summary">
                                <?= htmlspecialchars($summary) ?>
                            </p>

                            <div class="saved-post-date">
                                Đã lưu lúc <?= date('H:i', strtotime($post['saved_at'])) ?> ngày <?= date('d/m/Y', strtotime($post['saved_at'])) ?>
                            </div>

                        </div>

                        <button type="button" class="saved-bookmark-btn js-remove-saved"
                            data-post-id="<?= htmlspecialchars($post['post_id']) ?>" title="Bỏ lưu">
                            <i class="fa-solid fa-bookmark"></i>
                        </button>

                    </div>

                <?php endforeach; ?>

            </div>
            <?php if ($savedTotalPages > 1): ?>
                <div class="saved-pagination">

                    <?php if ($savedCurrentPage > 1): ?>
                        <button type="button" class="saved-page-btn js-saved-page" data-page="<?= $savedCurrentPage - 1 ?>">
                            ‹
                        </button>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $savedTotalPages; $i++): ?>
                        <button type="button" class="saved-page-btn js-saved-page <?= $i == $savedCurrentPage ? 'active' : '' ?>"
                            data-page="<?= $i ?>">
                            <?= $i ?>
                        </button>
                    <?php endfor; ?>

                    <?php if ($savedCurrentPage < $savedTotalPages): ?>
                        <button type="button" class="saved-page-btn js-saved-page" data-page="<?= $savedCurrentPage + 1 ?>">
                            ›
                        </button>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        <?php else: ?>

            <div class="saved-empty">
                <p class="mb-0">Bạn chưa lưu bài viết nào.</p>
            </div>

        <?php endif; ?>

    </div>
</div>