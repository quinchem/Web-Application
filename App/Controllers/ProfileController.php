<?php
/**
 * ProfileController.php
 */
namespace App\Controllers;

// Sửa đường dẫn trỏ tới ClientRepository
require_once __DIR__ . '/../../Repositories/ClientRepository.php';


class ProfileController
{
    private $clientRepository; // Đổi tên biến cho chuẩn

    public function __construct()
    {
        // Khởi tạo ClientRepository
        $this->clientRepository = new \ClientRepository();
    }

    public function changePassword()
    {
        require_once __DIR__ . '/../Views/Admin/Profile/change_password.php';
    }

    public function updatePassword()
    {
        $userId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: Admin_index.php?page=login');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = "Mật khẩu xác nhận không khớp!";
            header('Location: Admin_index.php?page=change_password');
            exit;
        }

        // Dùng $this->clientRepository thay vì userRepository
        $result = $this->clientRepository->changeUserPassword($userId, $currentPassword, $newPassword);

        if ($result === true) {
            $_SESSION['success_message'] = "Cập nhật mật khẩu thành công!";
        } else {
            $_SESSION['error_message'] = $result;
        }

        header('Location: Admin_index.php?page=change_password');
        exit;
    }
}