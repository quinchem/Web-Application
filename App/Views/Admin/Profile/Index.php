<div class="profile-menu" id="profileMenu">

    <div class="profile-menu-header">

        <div class="profile-menu-user">

            <img 
                src="/Web-Application/Public/Admin/Images/admin-avatar.png" 
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

    <a href="Index.php?page=change_password" class="profile-menu-item">
        <i class="fa-solid fa-lock"></i>
        Đổi mật khẩu
    </a>

</div>