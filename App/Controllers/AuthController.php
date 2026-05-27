<?php

namespace App\Controllers;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
class AuthController
{
    private $clientRepository;

    public function __construct()
    {
        require_once __DIR__ . '/../../Repositories/ClientRepository.php';

        $this->clientRepository = new \ClientRepository();
    }

    // Hiển thị giao diện đăng nhập
    public function loginForm()
    {
        require_once __DIR__ . '/../Views/Client/Auth/Login.php';
        exit();
    }

    // Xử lý đăng nhập
    public function loginProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = trim($_POST['user_name']);
            $password = $_POST['password'];

            $user = $this->clientRepository->getUserByUsername($username);

            // Trang trước đó
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=homepage';

            // Kiểm tra tài khoản
            if (
                $user &&
                $user['account_status'] === 'active' &&
                $user['role_id'] === 'RL0002' &&
                password_verify($password, $user['password'])
            ) {

                // Lưu session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['avatar'] = $user['avatar'];

                $_SESSION['success_msg'] = "Đăng nhập thành công!";

                // Lưu trang cần redirect
                $_SESSION['redirect_url'] =
                    (strpos($referer, 'page=login') !== false)
                    ? 'index.php?page=homepage'
                    : $referer;

                // Quay lại login để show SweetAlert
                header('Location: index.php?page=login');
                exit();

            } else {

                $_SESSION['error'] =
                    'Thông tin đăng nhập không chính xác hoặc tài khoản không hợp lệ.';

                header('Location: ' . $referer);
                exit();
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['role_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['full_name']);
        unset($_SESSION['avatar']);

        $referer = $_SERVER['HTTP_REFERER']
            ?? 'index.php?page=homepage';

        header("Location: " . $referer);
        exit();
    }
    //Quên mật khẩu
        public function forgotPasswordForm()
    {
        require_once __DIR__ .
        '/../Views/Client/Auth/Forgot_password.php';
    }
    // =====================================================
    // HANDLE FORGOT PASSWORD
    // =====================================================

    public function forgotPasswordProcess()
    {
        header('Content-Type: application/json');

        $email =
        trim($_POST['email'] ?? '');


        // =========================
        // VALIDATE EMAIL
        // =========================

        if (empty($email)) {

            echo json_encode([

                'status'  => 'error',

                'message' => 'Vui lòng nhập email'
            ]);

            exit();
        }


        // =========================
        // FIND USER
        // =========================

        $user =
        $this->clientRepository
        ->findByEmail($email);


        if (!$user) {

            echo json_encode([

                'status'  => 'error',

                'message' => 'Email không tồn tại'
            ]);

            exit();
        }


        try {

            // =========================
            // CREATE TOKEN
            // =========================

            $token =
            bin2hex(random_bytes(32));


            // TOKEN HẾT HẠN SAU 15 PHÚT

            $expiredAt =
            date(
                'Y-m-d H:i:s',
                strtotime('+15 minutes')
            );


            // =========================
            // RESET LINK
            // =========================

            $resetLink =

            "http://localhost/Web-Application/index.php?page=reset-password&token="

            .

            $token;


            // =========================
            // SAVE TOKEN
            // =========================

            $saved =

            $this->clientRepository
            ->saveResetToken(

                $user->user_id,

                $token,

                $expiredAt
            );


            if (!$saved) {

                echo json_encode([

                    'status'  => 'error',

                    'message' => 'Không thể lưu token'
                ]);

                exit();
            }


            // =========================
            // SEND MAIL
            // =========================

            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->Host =
            $_ENV['MAIL_HOST'];

            $mail->SMTPAuth = true;

            $mail->Username =
            $_ENV['MAIL_USERNAME'];

            $mail->Password =
            $_ENV['MAIL_PASSWORD'];

            $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port =
            $_ENV['MAIL_PORT'];

            $mail->CharSet = 'UTF-8';


            $mail->setFrom(

                $_ENV['MAIL_FROM'],

                $_ENV['MAIL_FROM_NAME']
            );

            $mail->addAddress($email);


            $mail->isHTML(true);

            $mail->Subject =
            'Khôi phục mật khẩu';

            $mail->Body = "

                <h2>Khôi phục mật khẩu</h2>

                <p>
                    Link có hiệu lực trong
                    <strong>15 phút</strong>
                </p>

                <a href='{$resetLink}'>
                    Nhấn vào đây để đổi mật khẩu
                </a>
            ";


            $mail->send();


            echo json_encode([

                'status'  => 'success',

                'message' =>
                'Đã gửi email khôi phục mật khẩu'
            ]);

        } catch (Exception $e) {

            echo json_encode([

                'status'  => 'error',

                'message' =>
                'Lỗi gửi mail: ' .
                $e->getMessage()
            ]);
        }

        exit();
    }

    //Đặt lại mật khẩu
        public function resetPasswordForm()
    {
        require_once __DIR__ .
        '/../Views/Client/Auth/Reset_password.php';
    }

    public function resetPasswordProcess()
    {
        header('Content-Type: application/json');

        $token =
            $_POST['token'] ?? '';

        $password =
            $_POST['password'] ?? '';

        $confirmPassword =
            $_POST['confirmPassword'] ?? '';


        // =========================
        // EMPTY
        // =========================

        if (

            empty($token)

            ||

            empty($password)

            ||

            empty($confirmPassword)

        ) {

            echo json_encode([

                'status' => 'error',

                'message' =>
                    'Vui lòng nhập đầy đủ thông tin'
            ]);

            exit();
        }


        // =========================
        // PASSWORD NOT MATCH
        // =========================

        if ($password !== $confirmPassword) {

            echo json_encode([

                'status' => 'error',

                'message' =>
                    'Mật khẩu xác nhận không khớp'
            ]);

            exit();
        }


        // =========================
        // CHECK TOKEN
        // =========================

        $user =
            $this->clientRepository
                ->findByResetToken($token);


        if (!$user) {

            echo json_encode([

                'status' => 'error',

                'message' =>
                    'Token không hợp lệ hoặc đã hết hạn'
            ]);

            exit();
        }


        // =========================
        // HASH PASSWORD
        // =========================

        $hashedPassword =

            password_hash(

                $password,

                PASSWORD_DEFAULT
            );


        // =========================
        // UPDATE PASSWORD
        // =========================

        $success =

            $this->clientRepository
                ->updatePasswordByToken(

                    $token,

                    $hashedPassword
                );


        if ($success) {

            echo json_encode([

                'status' => 'success',

                'message' =>
                    'Đổi mật khẩu thành công'
            ]);

        } else {

            echo json_encode([

                'status' => 'error',

                'message' =>
                    'Không thể cập nhật mật khẩu'
            ]);
        }

        exit();
    }

    // Đăng ký tài khoản
    public function registerForm()
    {
        require_once __DIR__ .
        '/../Views/Client/Auth/Register.php';
    }

        public function registerProcess()
{
    header('Content-Type: application/json');

    $fullName =
        trim($_POST['full_name'] ?? '');

    $userName =
        trim($_POST['user_name'] ?? '');

    $email =
        trim($_POST['email'] ?? '');

    $password =
        $_POST['password'] ?? '';

    $confirmPassword =
        $_POST['confirmPassword'] ?? '';


    // =========================
    // EMPTY
    // =========================

    if (
        empty($fullName) ||
        empty($userName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        echo json_encode([

            'status' => 'error',

            'message' =>
                'Vui lòng nhập đầy đủ thông tin'
        ]);

        exit();
    }


    // =========================
    // PASSWORD NOT MATCH
    // =========================

    if ($password !== $confirmPassword) {

        echo json_encode([

            'status' => 'error',

            'message' =>
                'Mật khẩu xác nhận không khớp'
        ]);

        exit();
    }


    // =========================
    // REGISTER
    // =========================

    $result =
        $this->clientRepository->register(

            $fullName,

            $userName,

            $email,

            $password
        );


    if ($result['status']) {

        echo json_encode([

            'status' => 'success',

            'message' =>
                $result['message']
        ]);

    } else {

        echo json_encode([

            'status' => 'error',

            'message' =>
                $result['message']
        ]);
    }

    exit();
}
}