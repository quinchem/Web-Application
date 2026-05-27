<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Quên mật khẩu - Trạm Tin Việt
    </title>

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Newsreader:wght@700&display=swap"
        rel="stylesheet"
    >

    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- BOOTSTRAP ICON -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    >

    <!-- CSS -->

    <link
        rel="stylesheet"
        href="Public/Client/Css/ClientForgotPassword.css"
    >

</head>

<body>

    <div class="forgot-card">

        <!-- BRAND -->

        <div class="brand-header">

            <div class="brand-line"></div>

            <div class="brand-name">
                Trạm Tin Việt
            </div>

        </div>


        <!-- TITLE -->

        <h1 class="forgot-title">
            Quên mật khẩu
        </h1>

        <p class="forgot-subtitle">

            Nhập email của bạn để nhận mã khôi phục mật khẩu

        </p>


        <!-- FORM -->

        <form id="forgotPasswordForm">

            <div class="form-label-custom">

                Địa chỉ Email

            </div>


            <!-- INPUT -->

            <div class="input-wrapper">

                <i class="bi bi-at"></i>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="username@email.com"
                    required
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                class="btn-submit"
            >

                Gửi yêu cầu

            </button>


            <!-- BACK -->

            <div class="back-login">

                <a href="index.php?page=login">

                    <i class="bi bi-arrow-left"></i>

                    Quay lại Đăng nhập

                </a>

            </div>

        </form>

    </div>


    <!-- SWEET ALERT -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JS -->

    <script src="Public/Client/Js/ClientForgotPassword.js"></script>

</body>

</html>