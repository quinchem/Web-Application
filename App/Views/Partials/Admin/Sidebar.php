<!-- App/Views/Partials/Admin/Sidebar.php -->
<?php
$currentPage = $_GET['page'] ?? '';
?>

<div class="sidebar">

    <div class="logo">TRẠM TIN VIỆT</div>

    <div class="menu">

        <a href="Index.php?page=dashboard"
           class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-table-columns"></i>
            Dashboard
        </a>

        <a href="Index.php?page=admin_posts"
           class="menu-item <?= in_array($currentPage, ['admin_posts', 'admin_user_posts']) ? 'active' : '' ?>">
            <i class="fa-regular fa-newspaper"></i>
            Quản lý bài viết
        </a>

        <a href="Index.php?page=admin_posts"
           class="sub-item <?= $currentPage === 'admin_posts' ? 'sub-active' : '' ?>">
            Bài viết Quản trị viên
        </a>

        <a href="Index.php?page=admin_user_posts"
           class="sub-item <?= $currentPage === 'admin_user_posts' ? 'sub-active' : '' ?>">
            Bài viết Người đọc
        </a>

        <a href="Index.php?page=readers"
           class="menu-item <?= $currentPage === 'readers' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            Quản lý người đọc
        </a>

    </div>

    <!-- PROFILE AREA -->
<div class="profile-wrapper" id="profileWrapper">

    <?php require_once __DIR__ . '/../../Admin/Profile/Index.php'; ?>

    <div class="admin-profile" onclick="toggleProfileMenu()" style="cursor:pointer;">
        <img src="/Web-Application/Public/Admin/Images/admin-avatar.png" alt="Admin">
        <div class="profile-info">
            <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong>
            <p>
                ĐĂNG XUẤT
                <i class="fa-solid fa-right-from-bracket"></i>
            </p>
        </div>
    </div>

</div>

</div>