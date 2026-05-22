<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Quên mật khẩu</title>

    <!-- FONT -->

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap"
          rel="stylesheet">

    <!-- ICON -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->

    <link rel="stylesheet"
          href="/Web-Application/Public/Admin/Css/Pages/ForgotPassword.css">

    <!-- SWEET ALERT -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="forgot-wrapper">

    <h1 class="logo">

        TRẠM TIN VIỆT

    </h1>


    <div class="forgot-card">

        <h2>

            Lấy lại mật khẩu

        </h2>

        <p class="description">

            Vui lòng nhập email để khôi phục mật khẩu.

        </p>


        <!-- FORM -->

        <form id="forgotForm">

            <div class="form-group">

                <label>

                    ĐỊA CHỈ EMAIL

                </label>

                <div class="input-group">

                    <i class="fa-regular fa-envelope"></i>

                    <input type="email"
                           name="email"
                           placeholder="username@gmail.com"
                           required>

                </div>

            </div>


            <button type="submit"
                    class="submit-btn">

                GỬI YÊU CẦU

                <i class="fa-solid fa-arrow-right"></i>

            </button>

        </form>


      <div class="back-login">

    <a href="http://localhost/Web-Application/Index.php?page=admin_login">

        <i class="fa-solid fa-arrow-left"></i>

        Quay lại đăng nhập

    </a>

</div>

    </div>

</div>

<script src="/Web-Application/Public/Admin/Js/forgotPassword.js"></script>

</body>
</html>