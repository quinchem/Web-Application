<?php
require_once __DIR__ . '/../../Configs/Database.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../../Repositories/ClientRepository.php';

class AdminController
{
    private $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
    }

    public function login()
    {
        session_start();

        if (isset($_COOKIE['remember_user'])) {
            $_SESSION['user'] = $_COOKIE['remember_user'];
            header("Location: /Web-Application/App/Views/Admin/Dashboard/Dashboard.php");
            exit();
        }

        require_once __DIR__ . '/../Views/Admin/Auth/Login.php';
    }

    public function handleLogin()
    {
        session_start();

        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';
        $remember = $_POST['remember'] ?? null;

        $user = $this->clientRepository->checkLogin($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;

            if ($remember) {
                setcookie('remember_user', $user->user_id, time() + (86400 * 30), "/");
            }

            // ✅ Có đủ html + body thì SweetAlert mới hiện được
            echo "
            <!DOCTYPE html>
            <html><body>
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
                    window.location.href = '/Web-Application/App/Views/Admin/Dashboard/Dashboard.php';
                }, 1800);
            </script>
            </body></html>
            ";
            exit();
        }

        echo "
        <!DOCTYPE html>
        <html><body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Đăng nhập thất bại',
                text: 'Sai email hoặc mật khẩu!',
                confirmButtonColor: '#d10016'
            }).then(() => {
                window.location.href = '/Web-Application/App/Views/Admin/Auth/Login.php';
            });
        </script>
        </body></html>
        ";
        exit();
    }

    public function logout()
    {
        session_start();
        session_destroy();
        setcookie('remember_user', '', time() - 3600, "/");
        header("Location: /Web-Application/App/Views/Admin/Auth/Login.php");
        exit();
    }
}

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleLogin();
} else {
    // ✅ Thêm else này — GET request thì load trang login
    $controller->login();
}