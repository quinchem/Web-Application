<?php

namespace App\Controllers;

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
                $password === $user['password']
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
}