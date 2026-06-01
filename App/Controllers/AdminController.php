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

    // Hàm hiển thị form đăng nhập Admin
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header(
            "Location: http://localhost/Web-Application/Admin_index.php?page=dashboard"
            );
            exit();
        }
        require_once __DIR__ .
        '/../Views/Admin/Auth/Login.php';
    }

    // Hàm xử lý đăng nhập từ form đăng nhập Admin
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
    
        // Thực hiện kiểm tra tài khoản và mật khẩu
        $user =
            $this->clientRepository
                ->checkLogin(
                    $email,
                    $password
                );

        if ($user) {
            $_SESSION['user'] = $user;

            // Xử lý Lưu đăng nhập
        if ($remember) {

            // Tạo token ngẫu nhiên và lưu vào database và cookie
            $token =
            bin2hex(random_bytes(32));

            $this->clientRepository
            ->saveRememberToken(
                $user->user_id,
                $token
            );
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

                setTimeout(() => { window.location.href = 'http://localhost/Web-Application/Admin_index.php?page=dashboard';}, 1800);
            </script>
            </body>
            </html> ";
            exit();
        }

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

            }).then(() => {window.location.href ='http://localhost/Web-Application/Admin_index.php?page=admin_login';});

        </script>
        </body>
        </html>";
        exit();
    }

    // Hàm xử lý đăng xuất của Admin
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
                session_start();}
    $_SESSION = [];
            session_destroy();
            setcookie(
                'remember_token',
                '',
                time() - 3600,
                "/"
            );
            header("Location: http://localhost/Web-Application/Admin_index.php?page=admin_login" );
            exit();
        }

    // Hàm hiển thị form quên mật khẩu
    public function forgotPassword()
    {
        require_once __DIR__ .
        '/../Views/Admin/Auth/Forgot_password.php';
    }

    // Hàm xử lý gửi email khôi phục mật khẩu
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

        // Sử dụng repository để tìm user theo email
        $user = $this->clientRepository->findByEmail($email);

        if (!$user) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Email không tồn tại trong hệ thống'
            ]);
            exit();
        }
        try 
        {
            $token     = bin2hex(random_bytes(32));
            $expiredAt = date('Y-m-d H:i:s', time() + 900); // Hết hạn sau 15 phút
            $resetLink = "http://localhost/Web-Application/Admin_index.php". "?page=admin_reset_password&token=" . $token;
            $saved = $this->clientRepository->saveResetToken(
                $user->user_id,
                $token,
                $expiredAt
            );

            if (!$saved) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Không thể lưu token, thử lại sau']);
                exit();
            }

            $mail = new PHPMailer(true);

            $mail->SMTPDebug  = 0; 
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
                <a href='{$resetLink}'>Nhấn đây để đổi mật khẩu</a> ";

            $mail->send();
            echo json_encode([
                'status'  => 'success',
                'message' => 'Email khôi phục đã được gửi, vui lòng kiểm tra hộp thư'
            ]);

        } catch (Exception $e) 
            {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Lỗi gửi mail: ' . $e->getMessage()
                ]);
            }
        exit();
    }

    // Hàm hiển thị form đổi mật khẩu
    public function resetPassword()
    {
    require_once __DIR__ .
    '/../Views/Admin/Auth/Reset_password.php';
    }

    // Hàm xử lý đổi mật khẩu qua AJAX
    public function resetPasswordAjax()
    {
        header('Content-Type: application/json');

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
    
        // Kiểm tra dữ liệu đầu vào
        if 
        (
            empty($token)
            ||
            empty($password)
            ||
            empty($confirmPassword)
        ) 
        {
            echo json_encode([
                'status' => false,
                'message' =>'Vui lòng nhập đầy đủ thông tin!' ]);
            exit();
        }


        // Xử lý kiểm tra mật khẩu xác nhận
        if ($password !== $confirmPassword) {
            echo json_encode([
                'status' => false,
                'message' =>'Mật khẩu xác nhận không khớp!']);
            exit();
        }

        // Sử dụng repository để tìm user theo token
        $user =  $this->clientRepository->findByResetToken($token);

        if (!$user) 
        {
            echo json_encode([
                'status' => false,
                'message' =>'Token không hợp lệ hoặc đã hết hạn!' ]);

            exit();
        }

        // Mã hóa mật khẩu mới trước khi lưu vào database
        $hashedPassword = password_hash( $password, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu mới cho user và xóa token
         $success = $this->clientRepository ->updatePasswordByToken($token, $hashedPassword);

        if ($success) 
            {
            echo json_encode([
                'status' => true,
                'message' =>'Đổi mật khẩu thành công!']);

            } 
        else 
            {
            echo json_encode([
                'status' => false,
                'message' => 'Không thể cập nhật mật khẩu!' ]);
            }
        exit();
    }
}