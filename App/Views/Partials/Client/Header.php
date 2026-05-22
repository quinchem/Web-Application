<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Newsreader:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/Style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Trạm Tin Việt</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .font-serif {
            font-family: 'Newsreader', serif;
        }

        /* Căn chỉnh lại menu dropdown cho đẹp trên nền tối của header */
        .auth-links .dropdown-menu {
            margin-top: 10px;
            border-radius: 8px;
        }

        .auth-links .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #b90c17 !important;
        }
    </style>
</head>

<body>
    <header class="header-container">
        <div class="container" style="width: 1140px;">
            <div class="header-top">
                <div class="header-meta" style="font-size: 0.8rem; font-weight: 600; display: flex; gap: 20px;">
                    <span>
                        <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>
                        <span id="current-date">Đang tải ngày tháng...</span>
                    </span>
                </div>

                <a class="logo-text" href="index.php?page=homepage">Trạm Tin Việt</a>

                <div class="auth-links"
                    style="font-size: 0.8rem; font-weight: 700; display: flex; gap: 15px; align-items: center;">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] === 'RL0002'): ?>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="<?= !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : '/Public/Admin/Images/admin-avatar.png'; ?>"
                                alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">

                            <span
                                style="color: white; text-transform: uppercase; font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                                <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['user_name']); ?>
                            </span>

                            <a href="index.php?page=logout"
                                style="color: white; text-decoration: none; border: 1px solid rgba(255,255,255,0.7); padding: 5px 12px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; margin-left: 8px; transition: 0.3s;">
                                ĐĂNG XUẤT
                            </a>
                        </div>

                    <?php else: ?>

                        <a href="index.php?page=register" style="color: white; text-decoration: none;">Đăng Ký</a>
                        <span style="opacity: 0.5; color: white;">|</span>
                        <a href="#" style="color: white; text-decoration: none;" data-bs-toggle="modal"
                            data-bs-target="#loginModal">Đăng Nhập</a>

                    <?php endif; ?>
                </div>
            </div>

            <nav class="nav-bar">
                <ul class="menu-items">
                    <li><a href="index.php?page=homepage" class="active">Trang Chủ</a></li>
                    <li><a href="index.php?page=category&name=Thời sự">Thời Sự</a></li>
                    <li><a href="index.php?page=category&name=Kinh tế">Kinh Tế</a></li>
                    <li><a href="index.php?page=category&name=Tiện ích">Tiện Ích</a></li>
                </ul>
                <div class="search-box" style="position: relative;">
                    <input type="text" placeholder="Tìm kiếm..."
                        style="padding: 8px 15px 8px 35px; border-radius: 10px; border: none; outline: none; width: 220px;">
                    <i class="fas fa-search"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bababa;"></i>
                </div>
            </nav>
        </div>
    </header>
    </body>
    <script src="/Public/Client/Js/Current_day.js"></script>
<?php if (isset($_SESSION['success_msg'])): ?>
        <script>
            alert('<?= $_SESSION['success_msg']; ?>');
        </script>
            <?php unset($_SESSION['success_msg']); // Xóa session sau khi hiện thông báo ?>
<?php endif; ?>
    
<?php if (isset($_SESSION['error'])): ?>
        <script>
            alert('<?= $_SESSION['error']; ?>');
        </script>
            <?php unset($_SESSION['error']); ?>
<?php endif; ?>