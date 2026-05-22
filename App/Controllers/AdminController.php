<?php

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

        // Remember Login

        if (isset($_COOKIE['remember_user'])) {

            $_SESSION['user'] =
            $_COOKIE['remember_user'];

            header(
            "Location: http://localhost/Web-Application/Index.php?page=admin_dashboard"
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

            if ($remember) {

                setcookie(

                    'remember_user',

                    $user->user_id,

                    time() + (86400 * 30),

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
'http://localhost/Web-Application/Index.php?page=admin_user_posts';

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
                'http://localhost/Web-Application/Index.php?page=admin_login';

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

        session_destroy();

        setcookie(
            'remember_user',
            '',
            time() - 3600,
            "/"
        );

        header(
        "Location: http://localhost/Web-Application/Index.php?page=admin_login"
        );

        exit();
    }


    // ====================================
    // FORGOT PASSWORD PAGE
    // ====================================

    public function forgotPassword()
    {
        require_once __DIR__ .
        '/../Views/Admin/Auth/ForgotPassword.php';
    }


    // ====================================
    // HANDLE FORGOT PASSWORD
    // ====================================

    public function handleForgotPassword()
    {
        header('Content-Type: application/json');

        $email =
        $_POST['email'] ?? '';


        // EMPTY EMAIL

        if (empty($email)) {

            echo json_encode([

                'status' => 'error',

                'message' =>
                'Vui lòng nhập email'

            ]);

            exit();
        }


        // TEST EMAIL

        if ($email === 'admin@gmail.com') {

            echo json_encode([

                'status' => 'success',

                'message' =>
                'Đã gửi email khôi phục mật khẩu'

            ]);

        } else {

            echo json_encode([

                'status' => 'error',

                'message' =>
                'Email không tồn tại trong hệ thống'

            ]);
        }

        exit();
    }

      public function resetPasswordAjax()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            // VALIDATE

            if (empty($password) || empty($confirmPassword)) {

                echo json_encode([
                    'status' => false,
                    'message' => 'Vui lòng nhập đầy đủ thông tin!'
                ]);

                return;
            }

            if ($password !== $confirmPassword) {

                echo json_encode([
                    'status' => false,
                    'message' => 'Mật khẩu xác nhận không khớp!'
                ]);

                return;
            }

            // TODO:
            // UPDATE PASSWORD DATABASE Ở ĐÂY

            echo json_encode([
                'status' => true,
                'message' => 'Đổi mật khẩu thành công!'
            ]);
        } else {

            echo json_encode([
                'status' => false,
                'message' => 'Yêu cầu không hợp lệ!'
            ]);
        }
}
}
