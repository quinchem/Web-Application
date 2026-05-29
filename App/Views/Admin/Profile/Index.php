<link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">

<div class="profile-menu" id="profileMenu">

    <div class="profile-menu-header border-bottom px-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <img 
                src="<?= htmlspecialchars($_SESSION['user']->avatar ?? 'Public/Admin/Images/admin-avatar.png') ?>" 
                alt="Admin Avatar"
                class="rounded-circle object-fit-cover"
                style="width: 58px; height: 58px;"
            >
            <div class="d-flex flex-column">
                <strong class="fw-black" style="font-size: 15px; color: #07344a;">
                    <?= htmlspecialchars($_SESSION['user']->full_name ?? '') ?>
                </strong>
                <span class="fw-medium" style="font-size: 13px; color: #8ea2b2;">
                    <?= htmlspecialchars($_SESSION['user']->email ?? '') ?>
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
