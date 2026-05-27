<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đăng ký - Trạm Tin Việt
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
        href="Public/Client/Css/ClientRegister.css"
    >

</head>

<body>

    <div class="register-card">


        <!-- BRAND -->

        <div class="brand-header">

            <div class="brand-line"></div>

            <div class="brand-name">

                Trạm Tin Việt

            </div>

        </div>


        <!-- TITLE -->

        <h1 class="register-title">

            Đăng ký tài khoản mới

        </h1>


        <!-- FORM -->

        <form id="registerForm">


            <!-- FULLNAME -->

            <div class="form-label-custom">

                Họ tên

            </div>

            <div class="input-wrapper">

                <i class="bi bi-person"></i>

                <input
                    type="text"
                    name="full_name"
                    placeholder="Nguyễn Văn A"
                    required
                >

            </div>
        <!-- USERNAME -->

        <div class="form-label-custom">
            Tên đăng nhập
        </div>

        <div class="input-wrapper">

            <i class="bi bi-person-circle"></i>

            <input
                type="text"
                name="user_name"
                placeholder="nguyenvana"
                required
            >

        </div>

            <!-- EMAIL -->

            <div class="form-label-custom">

                Email

            </div>

            <div class="input-wrapper">

                <i class="bi bi-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="email@example.com"
                    required
                >

            </div>


            <!-- PASSWORD -->

            <div class="form-label-custom">

                Mật khẩu

            </div>

            <div class="input-wrapper">

                <i class="bi bi-lock"></i>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                >

                <i
                    class="bi bi-eye toggle-password"
                    toggle="#password"
                ></i>

            </div>


            <!-- CONFIRM PASSWORD -->

            <div class="form-label-custom">

                Nhập lại mật khẩu

            </div>

            <div class="input-wrapper">

                <i class="bi bi-lock"></i>

                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="••••••••"
                    required
                >

                <i
                    class="bi bi-eye toggle-password"
                    toggle="#confirmPassword"
                ></i>

            </div>


            <!-- POLICY -->

            <div class="policy-wrapper">

                <input
                    type="checkbox"
                    id="policy"
                    required
                >

                <label for="policy">

                    Tôi đồng ý với
                    Điều khoản sử dụng
                    và

                    <strong>
                        Chính sách bảo mật
                    </strong>

                </label>

            </div>
            <!-- REGISTER BUTTON -->

            <button
                type="submit"
                class="btn-register"
            >

                Đăng ký

                <i class="bi bi-arrow-right"></i>

            </button>

            <!-- FOOTER -->

            <div class="auth-footer">

                Đã có tài khoản?

                <a href="index.php?page=login">

                    Đăng nhập ngay

                </a>

            </div>

        </form>

    </div>


    <!-- SWEET ALERT -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- JS -->

    <script src="Public/Client/Js/ClientRegister.js"></script>

</body>

</html>