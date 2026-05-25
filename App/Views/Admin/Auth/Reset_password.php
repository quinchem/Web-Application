<?php
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Thiết lập mật khẩu mới</title>

    <!-- GOOGLE FONT -->

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap"
          rel="stylesheet">

    <!-- FONT AWESOME -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->

    <link rel="stylesheet"
          href="/Web-Application/Public/Admin/Css/Pages/ResetPassword.css">

    <!-- SWEET ALERT -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="reset-wrapper">

    <!-- LOGO -->

    <h1 class="logo">
        TRẠM TIN VIỆT
    </h1>


    <!-- CARD -->

    <div class="reset-card">

        <h2>
            Thiết lập mật khẩu mới
        </h2>

        <p class="description">
            Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
        </p>


        <!-- FORM -->

        <form id="resetPasswordForm">

            <!-- TOKEN -->

            <input type="hidden"
                   name="token"
                   value="<?= htmlspecialchars($token) ?>">


            <!-- PASSWORD -->

            <div class="form-group">

                <label>
                    MẬT KHẨU MỚI
                </label>

                <div class="input-group">

                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Nhập mật khẩu mới"
                           required>

                    <i class="fa-regular fa-eye-slash toggle-password"></i>

                </div>

            </div>


            <!-- CONFIRM PASSWORD -->

            <div class="form-group">

                <label>
                    XÁC NHẬN MẬT KHẨU MỚI
                </label>

                <div class="input-group">

                    <input type="password"
                           id="confirmPassword"
                           name="confirmPassword"
                           placeholder="Nhập lại mật khẩu"
                           required>

                    <i class="fa-regular fa-eye toggle-password"></i>

                </div>

            </div>


            <!-- PASSWORD STRENGTH -->

            <div class="strength-container">

                <div class="strength-bar">

                    <div id="strength-fill"></div>

                </div>

                <p id="strength-text">
                    ĐỘ BẢO MẬT: CHƯA CÓ
                </p>

            </div>


            <!-- BUTTON -->

            <button type="submit"
                    class="submit-btn">

                Xác nhận

            </button>

        </form>


        <!-- BACK LOGIN -->

        <div class="back-login">

            <a href="http://localhost/Web-Application/Admin_index.php?page=admin_login">

                <i class="fa-solid fa-arrow-left"></i>

                Quay lại trang đăng nhập

            </a>

        </div>

    </div>

</div>


<!-- JQUERY -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- JS -->

<script src="/Web-Application/Public/Admin/Js/Pages/resetPassword.js"></script>

</body>
</html>