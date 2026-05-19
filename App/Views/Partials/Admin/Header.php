<?php
$breadcrumbs = $breadcrumbs ?? [];
?>

<div class="topbar">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <?php if ($index < count($breadcrumbs) - 1): ?>
                <a href="<?= htmlspecialchars($crumb['url'] ?? '#') ?>">
                    <?= htmlspecialchars($crumb['label']) ?>
                </a>
                <span>></span>
            <?php else: ?>
                <span class="active"><?= htmlspecialchars($crumb['label']) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>