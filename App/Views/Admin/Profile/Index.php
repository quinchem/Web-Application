<link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">

<div class="profile-menu" id="profileMenu">

    <div class="profile-menu-header border-bottom px-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <img 
                src="Public/Admin/Images/admin-avatar.png" 
                alt="Admin Avatar"
                class="rounded-circle object-fit-cover"
                style="width: 58px; height: 58px;"
            >
            <div class="d-flex flex-column">
                <strong class="fw-black" style="font-size: 15px; color: #07344a;">
                    <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Nguyễn Văn An') ?>
                </strong>
                <span class="fw-medium" style="font-size: 13px; color: #8ea2b2;">
                    <?= htmlspecialchars($_SESSION['admin_email'] ?? 'annguyen123@example.com') ?>
                </span>
            </div>
        </div>
    </div>

    <button type="button" class="profile-menu-item" id="btnOpenEditProfile">
        <i class="fa-regular fa-user"></i>
        Thông tin tài khoản
    </button>

    <button type="button" class="profile-menu-item" id="btnOpenChangePassword">
        <i class="fa-solid fa-shield-halved"></i>
        Đổi mật khẩu
    </button>

</div>

<?php require_once __DIR__ . '/edit.php'; ?>

<?php require_once __DIR__ . '/change_password.php'; ?>