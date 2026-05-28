<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Trạm Tin Việt</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Newsreader:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="Public/Client/Css/ClientAuth.css">
</head>

<body>

    <div class="auth-card">

        <div class="brand-header">
            <div class="brand-line"></div>
            <div class="brand-name">Trạm Tin Việt</div>
        </div>

        <h1 class="auth-title">Chào mừng trở lại</h1>
        <p class="auth-subtitle">Đăng nhập để cập nhật những tin tức mới nhất từ Trạm Tin Việt</p>

        <form action="index.php?page=login" method="POST">

            <div class="form-section-label">Đăng nhập</div>

            <div class="input-wrapper">
                <i class="bi bi-person"></i>
                <input type="text" name="user_name" placeholder="Tên đăng nhập" required>
            </div>

            <div class="input-wrapper">
                <i class="bi bi-lock"></i>
                <input type="password" name="password" id="standalonePasswordInput" placeholder="Mật khẩu" required>
                <i class="bi bi-eye eye-icon" id="standaloneTogglePassword"></i>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
            </div>

            <a href="index.php?page=forgot-password" class="forgot-password">Quên mật khẩu?</a>

            <button type="submit" class="btn-submit">Đăng nhập</button>


            <div class="auth-footer">
                Bạn chưa có tài khoản? <a href="index.php?page=register">Đăng ký ngay</a>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Thất bại!',
                text: '<?= $_SESSION['error']; ?>',
                confirmButtonColor: '#b90c17',
                confirmButtonText: 'Thử lại'
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '<?= $_SESSION['success_msg']; ?>',
                showConfirmButton: false, // Ẩn nút OK để tự nó chạy
                timer: 1500, // Hiện 1.5 giây
                timerProgressBar: true
            }).then(() => {
                // Popup tắt xong sẽ kích hoạt JS tự động điều hướng
                window.location.href = '<?= $_SESSION['redirect_url'] ?? 'index.php?page=homepage'; ?>';
            });
        </script>
        <?php
        // Dọn dẹp session sau khi đã dùng xong
        unset($_SESSION['success_msg']);
        unset($_SESSION['redirect_url']);
        ?>
    <?php endif; ?>

    <script src="Public/Client/Js/ClientAuth.js"></script>
</body>

</html>