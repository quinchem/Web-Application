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
        <p class="auth-subtitle">Đăng nhập để cập nhật những tin tức mới nhất<br>từ Trạm Tin Việt</p>

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

            <a href="index.php?page=forgot-password" class="forgot-password">Quên mật khẩu?</a>

            <button type="submit" class="btn-submit">Đăng nhập</button>

            <button type="button" class="btn-google">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Tiếp tục với Google
            </button>

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
