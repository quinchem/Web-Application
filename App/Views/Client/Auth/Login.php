<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Trạm Tin Việt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            /* ẢNH NỀN TOÀN MÀN HÌNH (Bạn có thể đổi link ảnh khác nếu muốn) */
            background: url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        /* Lớp phủ tối mờ (Overlay) màu xanh đen của Trạm Tin Việt */
        .bg-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 48, 73, 0.75); 
            z-index: 1;
        }

        /* Vùng chứa Form được đưa lên trên lớp phủ */
        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        /* Thẻ Card chứa Form bo góc, đổ bóng xịn xò */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: none;
            padding: 2.5rem 2rem;
        }

        .brand-title {
            color: #0f3460;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .brand-line {
            width: 30px;
            height: 3px;
            background-color: #c92a2a;
            display: inline-block;
            margin-right: 12px;
            vertical-align: middle;
            border-radius: 2px;
        }

        .input-group-text, .form-control {
            background-color: #f4f7f6;
            border: 1px solid #e9ecef;
            padding: 0.8rem 1rem;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff;
            border-color: #c92a2a;
        }

        .btn-primary-custom {
            background-color: #c92a2a;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #b02525;
            transform: translateY(-2px);
        }

        .btn-google {
            border: 1px solid #dee2e6;
            background-color: #ffffff;
            color: #495057;
            padding: 0.8rem;
            font-weight: 500;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="bg-overlay"></div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="mb-4 text-center">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <span class="brand-line"></span>
                    <span class="brand-title">Trạm Tin Việt</span>
                </div>
                <h4 class="fw-bold mb-1" style="color: #333;">Chào mừng trở lại!</h4>
                <p class="text-muted small">Cập nhật tin tức mới nhất mỗi ngày</p>
            </div>

            <form action="index.php?page=login" method="POST">
                
                <div class="input-group mb-3">
                    <span class="input-group-text text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="user_name" class="form-control" placeholder="Tên đăng nhập" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="standalonePasswordInput" class="form-control" placeholder="Mật khẩu" required>
                    <span class="input-group-text text-muted" id="standaloneTogglePassword" style="cursor: pointer;">
                        <i class="bi bi-eye" id="standaloneEyeIcon"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe" style="cursor: pointer;">
                            Ghi nhớ tôi
                        </label>
                    </div>
                    <a href="index.php?page=forgot-password" class="text-decoration-none small fw-semibold" style="color: #c92a2a;">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-primary-custom text-white w-100 mb-3 shadow-sm">Đăng Nhập</button>
                
                <div class="position-relative text-center my-3">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white small text-muted" style="border-radius: 4px;">hoặc</span>
                </div>

                <button type="button" class="btn btn-google w-100 mb-4 d-flex justify-content-center align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16" style="color: #ea4335;">
                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                    </svg>
                    Tiếp tục với Google
                </button>

                <div class="text-center small">
                    <span class="text-muted">Bạn chưa có tài khoản?</span> 
                    <a href="index.php?page=register" class="text-decoration-none fw-bold" style="color: #c92a2a;">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            alert('<?= $_SESSION['error']; ?>');
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        document.getElementById('standaloneTogglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('standalonePasswordInput');
            const eyeIcon = document.getElementById('standaloneEyeIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            passwordInput.setAttribute('type', type);
            if (type === 'password') {
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    </script>
</body>
</html>