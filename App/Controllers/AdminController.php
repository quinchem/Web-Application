<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require_once __DIR__ .
'/../../Repositories/ClientRepository.php';

class AdminController
{
    private $clientRepository;

    public function __construct()
    {
        $this->clientRepository =
            new ClientRepository();
    }


    // ====================================
    // LOGIN PAGE
    // ====================================

public function login()
{
    if (session_status() === PHP_SESSION_NONE) {

        session_start();
    }

    // NẾU ĐÃ LOGIN
    // => KHÔNG CHO VÀO LOGIN NỮA

    if (isset($_SESSION['user'])) {

        header(

        "Location: http://localhost/Web-Application/Admin_index.php?page=dashboard"

        );

        exit();
    }

    require_once __DIR__ .
    '/../Views/Admin/Auth/Login.php';
}

    // ====================================
    // HANDLE LOGIN
    // ====================================

    public function handleLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }

        $email =
            $_POST['email'] ?? '';

        $password =
            $_POST['password'] ?? '';

        $remember =
            $_POST['remember'] ?? null;


        // CHECK LOGIN

        $user =
            $this->clientRepository
                ->checkLogin(
                    $email,
                    $password
                );


        // LOGIN SUCCESS

        if ($user) {

            $_SESSION['user'] = $user;


            // REMEMBER LOGIN

            // REMEMBER LOGIN

if ($remember) {

    // TẠO TOKEN RANDOM

    $token =
    bin2hex(random_bytes(32));

    // LƯU DATABASE

    $this->clientRepository
    ->saveRememberToken(

        $user->user_id,

        $token
    );

    // LƯU COOKIE

    setcookie(

        'remember_token',

        $token,

        time() + (86400 * 10),

        "/"
    );
}


            echo "

            <!DOCTYPE html>

            <html>

            <body>

            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

            <script>

                Swal.fire({

                    icon: 'success',

                    title: 'Đăng nhập thành công!',

                    text: 'Đang chuyển đến Dashboard...',

                    showConfirmButton: false,

                    timer: 1800

                });

                setTimeout(() => {

                    window.location.href =
'http://localhost/Web-Application/Admin_index.php?page=dashboard';

                }, 1800);

            </script>

            </body>

            </html>

            ";

            exit();
        }


        // LOGIN FAILED

        echo "

        <!DOCTYPE html>

        <html>

        <body>

        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

        <script>

            Swal.fire({

                icon: 'error',

                title: 'Đăng nhập thất bại',

                text: 'Sai email hoặc mật khẩu!',

                confirmButtonColor: '#d10016'

            }).then(() => {

                window.location.href =
                'http://localhost/Web-Application/Admin_index.php?page=admin_login';

            });

        </script>

        </body>

        </html>

        ";

        exit();
    }


    // ====================================
    // DASHBOARD
    // ====================================

    public function dashboard()
    {
        require_once __DIR__ .
            '/../Views/Admin/Dashboard/Dashboard.php';
    }


    // ====================================
    // LOGOUT
    // ====================================

public function logout()
{
    if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }
$_SESSION = [];
        session_destroy();

        setcookie(
            'remember_token',
            '',
            time() - 3600,
            "/"
        );

        header(
        "Location: http://localhost/Web-Application/Admin_index.php?page=admin_login"
        );

        exit();
    }
    // ====================================
    // FORGOT PASSWORD PAGE
    // ====================================

public function forgotPassword()
{
    require_once __DIR__ .
    '/../Views/Admin/Auth/Forgot_password.php';
}


    // ====================================
    // HANDLE FORGOT PASSWORD
    // ====================================

public function handleForgotPassword()
{
    header('Content-Type: application/json');

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Vui lòng nhập email'
        ]);
        exit();
    }

    // Tìm user theo email trước
    $user = $this->clientRepository->findByEmail($email);

    if (!$user) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Email không tồn tại trong hệ thống'
        ]);
        exit();
    }

    try {
        $token     = bin2hex(random_bytes(32));
        $expiredAt = date('Y-m-d H:i:s', time() + 900); // Hết hạn sau 15 phút
        $resetLink = "http://localhost/Web-Application/Admin_index.php"
                   . "?page=admin_reset_password&token=" . $token;

        // ✅ FIX: Truyền đúng thứ tự ($userId, $token, $expiredAt)
        $saved = $this->clientRepository->saveResetToken(
            $user->user_id,
            $token,
            $expiredAt
        );

        if (!$saved) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Không thể lưu token, thử lại sau'
            ]);
            exit();
        }

        $mail = new PHPMailer(true);

        $mail->SMTPDebug  = 0; // ✅ Tắt debug output
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Đặt lại mật khẩu';
        $mail->Body    = "
            <h2>Khôi phục mật khẩu</h2>
            <p>Link có hiệu lực trong <strong>15 phút</strong>.</p>
            <a href='{$resetLink}'>Nhấn đây để đổi mật khẩu</a>
        ";

        $mail->send();

        echo json_encode([
            'status'  => 'success',
            'message' => 'Email khôi phục đã được gửi, vui lòng kiểm tra hộp thư'
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Lỗi gửi mail: ' . $e->getMessage()
        ]);
    }

    exit();
}

    public function autoLogin()
{
    if (

        isset($_COOKIE['remember_token'])

        &&

        !isset($_SESSION['user'])

    ) {

        $user =

        $this->clientRepository
        ->findByRememberToken(

            $_COOKIE['remember_token']
        );

        if ($user) {

            $_SESSION['user'] = $user;
        }
    }
}
    public function resetPassword()
    {
    require_once __DIR__ .
    '/../Views/Admin/Auth/Reset_password.php';
    }
public function resetPasswordAjax()
{
    header('Content-Type: application/json');

    $token =
    $_POST['token'] ?? '';

    $password =
    $_POST['password'] ?? '';

    $confirmPassword =
    $_POST['confirmPassword'] ?? '';


    // =========================
    // VALIDATE
    // =========================

    if (

        empty($token)

        ||

        empty($password)

        ||

        empty($confirmPassword)

    ) {

        echo json_encode([

            'status' => false,

            'message' =>
            'Vui lòng nhập đầy đủ thông tin!'
        ]);

        exit();
    }


    // PASSWORD KHÔNG KHỚP

    if ($password !== $confirmPassword) {

        echo json_encode([

            'status' => false,

            'message' =>
            'Mật khẩu xác nhận không khớp!'
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

            'status' => false,

            'message' =>
            'Token không hợp lệ hoặc đã hết hạn!'
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

            'status' => true,

            'message' =>
            'Đổi mật khẩu thành công!'
        ]);

    } else {

        echo json_encode([

            'status' => false,

            'message' =>
            'Không thể cập nhật mật khẩu!'
        ]);
    }

    exit();
}
}