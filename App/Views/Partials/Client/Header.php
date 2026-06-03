<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/Client_Global.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Trạm Tin Việt</title>
</head>
<?php
$currentPage = !empty($_GET['page']) ? $_GET['page'] : 'homepage';
$currentCategory = !empty($_GET['name']) ? $_GET['name'] : '';
?>

<body>
    <header class="header-container">
        <div class="container" style="width: 1140px;">
            <div class="header-top">
                <div class="header-meta">
                    <span>
                        <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>
                        <span class="current-date" id="current-date">Đang tải ngày tháng...</span>
                    </span>
                </div>

                <a class="logo-text" href="index.php?page=homepage">Trạm Tin Việt</a>

                <div class="auth-links">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] === 'RL0002'): ?>

                        <div>
                            <img src="<?= !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : '/Public/Admin/Images/admin-avatar.png'; ?>"
                                alt="Avatar"
                                style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px;">

                            <a href="index.php?page=client_profile" style="text-decoration: none !important;">
                                <span
                                    style="color: white; text-transform: uppercase; font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px; ">
                                    <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['user_name']); ?>
                                </span>
                            </a>

                            <a class="logout" href="index.php?page=logout">
                                ĐĂNG XUẤT
                            </a>
                        </div>

                    <?php else: ?>

                        <a href="index.php?page=register" style="color: white; text-decoration: none;">ĐĂNG KÝ</a>
                        <span style="opacity: 0.5; color: white;">|</span>
                        <a href="index.php?page=login" style="color: white; text-decoration: none;">ĐĂNG NHẬP</a>

                    <?php endif; ?>
                </div>
            </div>

            <nav class="nav-bar">
                <ul class="menu-items">
                    <li>
                        <a href="index.php?page=homepage"
                            class="<?= ($currentPage === 'homepage') ? 'active' : ''; ?>">Trang Chủ</a>
                    </li>

<li class="nav-dropdown">
    <a href="index.php?page=category_detail&slug=thoi-su"
       class="<?= ($currentPage === 'category_detail' && ($_GET['slug'] ?? '') === 'thoi-su') ? 'active' : ''; ?>">
        Thời Sự
        <i class="fas fa-chevron-down dropdown-icon"></i>
    </a>

    <ul class="dropdown-menu-custom">

        <li>
            <a href="index.php?page=subcategory&parent=thoi-su&slug=chinh-tri">Chính trị</a>
        </li>
        <li>
            <a href="index.php?page=subcategory&parent=thoi-su&slug=xa-hoi">Xã hội</a>
        </li>
                <li>
            <a href="index.php?page=subcategory&parent=thoi-su&slug=quan-su">Quân sự</a>
        </li>
    </ul>
</li>

<li class="nav-dropdown">
    <a href="index.php?page=category_detail&slug=kinh-te"
       class="<?= ($currentPage === 'category_detail' && ($_GET['slug'] ?? '') === 'kinh-te') ? 'active' : ''; ?>">
        Kinh Tế
        <i class="fas fa-chevron-down dropdown-icon"></i>
    </a>

    <ul class="dropdown-menu-custom">
        <li>
            <a href="index.php?page=subcategory&parent=kinh-te&slug=thi-truong">Thị trường</a>
        </li>
        <li>
            <a href="index.php?page=subcategory&parent=kinh-te&slug=chung-khoan">Chứng khoán</a>
        </li>
        <li>
            <a href="index.php?page=subcategory&parent=kinh-te&slug=ngan-hang">Ngân hàng</a>
        </li>
        <li>
            <a href="index.php?page=subcategory&parent=kinh-te&slug=doanh-nghiep">Doanh nghiệp</a>
        </li>
    </ul>
</li>
                </ul>
                <form class="search-box" action="index.php" method="GET" style="position: relative;">
                    <input type="hidden" name="page" value="search_result">

                    <input type="text" name="key" placeholder="Tìm kiếm..." autocomplete="off"
                        style="padding: 8px 15px 8px 35px; border-radius: 10px; border: none; outline: none; width: 220px;">

                    <button type="submit" style="display: none;"></button>

                    <i class="fas fa-search"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bababa;">
                    </i>
                </form>
            </nav>
        </div>
    </header>
</body>
<script src="/../Web-Application/Public/Client/Js/Current_date.js"></script>
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