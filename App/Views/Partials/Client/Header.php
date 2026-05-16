<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Newsreader:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/Style.css">
    <title>Trạm Tin Việt</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .font-serif { font-family: 'Newsreader', serif; }
    </style>
</head>
<body>
<header class="header-container">
    <div class="container">
        <div class="header-top">
            <div class="header-meta" style="font-size: 0.8rem; font-weight: 600; display: flex; gap: 20px;">
                <span><i class="fas fa-calendar-alt"></i> <span id="live-date"></span></span>
            </div>
            <a class="logo-text" href="index.php?page=homepage">Trạm Tin Việt</a>
            <div class="auth-links" style="font-size: 0.8rem; font-weight: 700; display: flex; gap: 15px;">
                <a href="index.php?page=register" style="color: white; text-decoration: none;">Đăng Ký</a>
                <span style="opacity: 0.3;">|</span>
                <a href="index.php?page=login" style="color: white; text-decoration: none;">Đăng Nhập</a>
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
                <input type="text" placeholder="Tìm kiếm..." style="padding: 8px 15px 8px 35px; border-radius: 10px; border: none; outline: none; width: 220px;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: gray;"></i>
            </div>
        </nav>
    </div>
</header>
</body>
</html>