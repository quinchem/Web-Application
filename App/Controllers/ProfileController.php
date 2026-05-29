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
    header('Content-Type: application/json');

    $userId = (string)($_SESSION['user']->user_id ?? null);

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Phiên đăng nhập hết hạn.']);
        exit;
    }

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password']     ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp!']);
        exit;
    }

    if (strlen($newPassword) < 8) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 8 ký tự!']);
        exit;
    }

    try {
        // Lấy password hiện tại từ DB để verify thủ công
        // vì changeUserPassword dùng PARAM_INT sẽ lỗi với string ID
        $conn = (new \Database())->connect();

        $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy tài khoản!']);
            exit;
        }

        // Verify mật khẩu hiện tại — hỗ trợ cả plain text lẫn hash
        $isCorrect = password_verify($currentPassword, $user['password'])
                  || $currentPassword === $user['password'];

        if (!$isCorrect) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không chính xác!']);
            exit;
        }

        // Hash mật khẩu mới và update
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $upStmt = $conn->prepare("UPDATE user SET password = :pwd WHERE user_id = :id");
        $ok     = $upStmt->execute([':pwd' => $hashed, ':id' => $userId]);

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại, vui lòng thử lại.']);
        }

    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
    }

    exit;
}

    // ── Xử lý cập nhật hồ sơ (POST — AJAX, trả JSON) ────────────────────────
    public function updateProfile()
{
    header('Content-Type: application/json');

    // ✅ THAY: lấy đúng từ $_SESSION['user'] object
    $userId = $_SESSION['user']->user_id ?? null;

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
        $uploadDir = __DIR__ . '/../../Public/Admin/Images/Avatars/';
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
        // ✅ THAY: cập nhật đúng $_SESSION['user'] object
        $_SESSION['user']->full_name = $fullname;
        $_SESSION['user']->user_name = $username;
        $_SESSION['user']->email     = $email;
        $_SESSION['user']->bio       = $bio;
        if ($avatarPath) $_SESSION['user']->avatar = $avatarPath;

        echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => is_string($result) ? $result : 'Cập nhật thất bại, vui lòng thử lại.']);
    }
    exit;
}

    // ── Xử lý đổi mật khẩu (POST) ───────────────────────────────────────────
    public function updatePassword()
{
    // ✅ THAY:
    $userId = $_SESSION['user']->user_id ?? null;

    if (!$userId) {
        header('Location: Admin_index.php?page=admin_login');
        exit;
    }

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password']     ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp!';
        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    $result = $this->clientRepository->changeUserPassword($userId, $currentPassword, $newPassword);

    if ($result === true) {
        $_SESSION['success_message'] = 'Cập nhật mật khẩu thành công!';
    } else {
        $_SESSION['error_message'] = is_string($result) ? $result : 'Đổi mật khẩu thất bại.';
    }

    header('Location: Admin_index.php?page=admin_user_posts');
    exit;
}
}