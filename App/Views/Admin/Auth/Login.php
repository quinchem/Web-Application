<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập Admin</title>

    <!-- FONT -->

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&display=swap"
          rel="stylesheet">

    <!-- ICON -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->

<link rel="stylesheet"
      href="http://localhost/Web-Application/Public/Admin/Css/Pages/Login.css">

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <!-- LOGO -->

        <h1 class="logo">
            TRẠM TIN VIỆT
        </h1>


        <!-- FORM -->

        <form
        action="http://localhost/Web-Application/Admin_index.php?page=admin_login"
        method="POST">

            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    EMAIL NGƯỜI ĐỌC
                </label>

                <div class="input-group">

                    <input type="email"
                           name="email"
                           placeholder="admin@gmail.com"
                           required>

                </div>

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label>
                    MẬT KHẨU
                </label>

                <div class="input-group">

                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required>

                    <i class="fa-regular fa-eye toggle-password"
                       id="togglePassword"></i>

                </div>

            </div>


            <!-- OPTIONS -->

            <div class="login-options">

                <label class="remember-box">

                    <input type="checkbox"
                           name="remember">

                    <span>
                        Ghi nhớ đăng nhập
                    </span>

                </label>

                <a href="http://localhost/Web-Application/Admin_index.php?page=forgot_password"
                   class="forgot-password">

                    Quên mật khẩu?

                </a>

            </div>


            <!-- BUTTON -->

            <button type="submit"
                    class="login-btn">

                ĐĂNG NHẬP

            </button>

        </form>


        <!-- FOOTER -->

    </div>

</div>

<script src="http://localhost/Web-Application/Public/Admin/Js/Pages/login.js"></script>

</body>
</html>