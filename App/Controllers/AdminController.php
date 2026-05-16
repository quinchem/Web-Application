<?php
echo "ADMIN CONTROLLER RUNNING";
require_once __DIR__ . '/../../Repositories/ClientRepository.php';

class AdminController
{
    private $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
    }


    // =========================
    // HANDLE LOGIN
    // =========================

    public function handleLogin()
    {
        session_start();

        $email = $_POST['email'] ?? '';

        $password = $_POST['password'] ?? '';


        // CHECK LOGIN

        $user = $this->clientRepository->checkLogin(
            $email,
            $password
        );


        // =========================
        // SUCCESS
        // =========================

        if ($user) {

            $_SESSION['user'] = $user;

            echo "

            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

            <script>

                Swal.fire({

                    icon: 'success',

                    title: 'Đăng nhập thành công!',

                    text: 'Đang chuyển trang...',

                    showConfirmButton: false,

                    timer: 1800

                });

                setTimeout(() => {

                    window.location.href =
                    '../Views/Admin/Dashboard/Dashboard.php';

                }, 1800);

            </script>

            ";

            exit();
        }


        // =========================
        // FAILED
        // =========================

        echo "

        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

        <script>

            Swal.fire({

                icon: 'error',

                title: 'Đăng nhập thất bại',

                text: 'Sai email hoặc mật khẩu!',

                confirmButtonColor: '#d10016'

            }).then(() => {

                window.location.href =
                '../Views/Admin/Login/Login.php';

            });

        </script>

        ";
    }
}



// =========================
// RUN CONTROLLER
// =========================

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller->handleLogin();
}