<!-- App/Views/Partials/Admin/Sidebar.php -->

<?php
$currentPage = $_GET['page'] ?? '';
$postPages   = ['admin_posts', 'admin_user_posts', 'create_post', 'edit_post', 'review_post_page'];
?>

<div class="sidebar">

    <div class="logo">
        TRẠM TIN VIỆT
    </div>

    <div class="menu">

        <!-- DASHBOARD -->
        <a href="Admin_index.php?page=dashboard"
           class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-table-columns"></i>
            Dashboard
        </a>

        <!-- POSTS -->
        <a href="Admin_index.php?page=admin_posts"
           class="menu-item <?= in_array($currentPage, $postPages) ? 'active' : '' ?>">
            <i class="fa-regular fa-newspaper"></i>
            Quản lý bài viết
        </a>

        <!-- ADMIN POSTS -->
        <a href="Admin_index.php?page=admin_posts"
           class="sub-item <?= in_array($currentPage, ['admin_posts', 'create_post', 'edit_post']) ? 'sub-active' : '' ?>">
            Bài viết Quản trị viên
        </a>

        <!-- USER POSTS -->
        <a href="Admin_index.php?page=admin_user_posts"
           class="sub-item <?= in_array($currentPage, ['admin_user_posts', 'review_post_page']) ? 'sub-active' : '' ?>">
            Bài viết Người đọc
        </a>

        <!-- READERS -->
        <a href="Admin_index.php?page=readers"
           class="menu-item <?= $currentPage === 'readers' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            Quản lý người đọc
        </a>

    </div>

    <!-- PROFILE / LOGOUT -->
    <div class="profile-wrapper" id="profileWrapper">

        <?php require_once __DIR__ . '/../../Admin/Profile/Index.php'; ?>

        <div class="admin-profile" id="adminProfile">

            <img
                src="<?= htmlspecialchars($_SESSION['user']->avatar ?? '/Web-Application/Public/Admin/Images/admin-avatar.png') ?>"
                alt="Admin"
            >

            <div class="profile-info">

                <strong>
                    <?= htmlspecialchars($_SESSION['user']->full_name ?? 'Admin') ?>
                </strong>

                <p>
                    <span class="logout-btn"
                          onclick="
                              event.stopPropagation();
                              if(confirm('Bạn có chắc muốn đăng xuất không?')){
                                  window.location.href='Admin_index.php?page=logout';
                              }
                          ">
                        ĐĂNG XUẤT
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </span>
                </p>

            </div>

        </div>

    </div>

</div>