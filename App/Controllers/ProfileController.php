<?php
/**
 * ProfileController.php
 */
namespace App\Controllers;

require_once __DIR__ . '/../../Repositories/ClientRepository.php';

class ProfileController
{
    private $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new \ClientRepository();
    }

    // ── Hiển thị form đổi mật khẩu (GET) ────────────────────────────────────
    public function changePassword()
    {
        require_once __DIR__ . '/../Views/Admin/Profile/change_password.php';
    }

    // ── Xử lý cập nhật hồ sơ (POST — AJAX, trả JSON) ────────────────────────
    public function updateProfile()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Phiên đăng nhập hết hạn.']);
            exit;
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $bio      = trim($_POST['bio']      ?? '');

        // Xử lý upload avatar nếu có
        $avatarPath = null;
        if (!empty($_FILES['avatar']['name'])) {
            $uploadDir  = __DIR__ . '/../../Public/Admin/Images/Avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext        = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName   = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                $avatarPath = 'Public/Admin/Images/Avatars/' . $fileName;
            }
        }

        $result = $this->clientRepository->updateAdminProfile(
            $userId, $fullname, $username, $email, $bio, $avatarPath
        );

        if ($result === true) {
            // Cập nhật SESSION để UI phản ánh ngay
            $_SESSION['admin_name']     = $fullname;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_email']    = $email;
            $_SESSION['admin_bio']      = $bio;
            if ($avatarPath) $_SESSION['admin_avatar'] = $avatarPath;

            echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => is_string($result) ? $result : 'Cập nhật thất bại, vui lòng thử lại.']);
        }
        exit;
    }

    // ── Xử lý đổi mật khẩu (POST) ───────────────────────────────────────────
    public function updatePassword()
    {
        $userId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: Index.php?page=login');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password']     ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: Index.php?page=admin_user_posts');
            exit;
        }

        $result = $this->clientRepository->changeUserPassword($userId, $currentPassword, $newPassword);

        if ($result === true) {
            $_SESSION['success_message'] = 'Cập nhật mật khẩu thành công!';
        } else {
            $_SESSION['error_message'] = is_string($result) ? $result : 'Đổi mật khẩu thất bại.';
        }

        header('Location: Index.php?page=admin_user_posts');
        exit;
    }
}