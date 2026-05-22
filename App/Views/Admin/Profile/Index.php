<link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">

<div class="profile-menu" id="profileMenu">

    <div class="profile-menu-header">
        <div class="profile-menu-user">
            <img 
                src="Public/Admin/Images/admin-avatar.png" 
                alt="Admin Avatar"
            >
            <div class="profile-menu-info">
                <strong>
                    <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Nguyễn Văn An') ?>
                </strong>
                <span>
                    <?= htmlspecialchars($_SESSION['admin_email'] ?? 'annguyen123@example.com') ?>
                </span>
            </div>
        </div>
    </div>

    <a href="Index.php?page=profile" class="profile-menu-item">
        <i class="fa-regular fa-user"></i>
        Thông tin tài khoản
    </a>

    <button type="button" class="profile-menu-item" id="btnOpenChangePassword">
        <i class="fa-solid fa-shield-halved"></i>
        Đổi mật khẩu
    </button>

</div>

<?php require_once __DIR__ . '/change_password.php'; ?>