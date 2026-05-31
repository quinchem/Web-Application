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

    // ── Hiển thị form đổi mật khẩu (GET) 
    public function changePassword()
    {
        header('Content-Type: application/json');

        $userId = (string) ($_SESSION['user']->user_id ?? null);

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Phiên đăng nhập hết hạn.']);
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
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
            $ok = $upStmt->execute([':pwd' => $hashed, ':id' => $userId]);

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

    // ── Xử lý cập nhật hồ sơ (POST — AJAX, trả JSON) 
    public function updateProfile()
{
    header('Content-Type: application/json; charset=utf-8');

    $userId = $_SESSION['user']->user_id ?? null;

    if (!$userId) {
        echo json_encode([
            'success' => false,
            'message' => 'Phiên đăng nhập hết hạn.'
        ]);
        exit;
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');

    $avatarPath = null;

    // UPLOAD AVATAR LÊN CLOUDINARY
    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {

        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                'success' => false,
                'message' => 'Ảnh avatar bị lỗi khi upload. Mã lỗi: ' . $_FILES['avatar']['error']
            ]);
            exit;
        }

        $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
        $apiKey    = $_ENV['CLOUDINARY_API_KEY'] ?? '';
        $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu cấu hình Cloudinary trong file .env.'
            ]);
            exit;
        }

        $fileTmp  = $_FILES['avatar']['tmp_name'];
        $fileType = $_FILES['avatar']['type'];
        $fileName = $_FILES['avatar']['name'];

        $folder    = 'tramtinviet/avatar';
        $timestamp = time();

        $signature = sha1(
            "folder={$folder}&timestamp={$timestamp}{$apiSecret}"
        );

        $cfile = new \CURLFile($fileTmp, $fileType, $fileName);

        $data = [
            'file'      => $cfile,
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder'    => $folder
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($response === false) {
            echo json_encode([
                'success' => false,
                'message' => 'Không kết nối được Cloudinary: ' . $curlError
            ]);
            exit;
        }

        $uploadResult = json_decode($response, true);

        if ($httpCode !== 200 || !isset($uploadResult['secure_url'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Cloudinary từ chối upload ảnh.',
                'error'   => $uploadResult['error']['message'] ?? 'Không rõ lỗi.'
            ]);
            exit;
        }

        $avatarPath = $uploadResult['secure_url'];
    }

    // CẬP NHẬT DATABASE
    $updateResult = $this->clientRepository->updateAdminProfile(
        $userId,
        $fullname,
        $username,
        $email,
        $bio,
        $avatarPath
    );

    if ($updateResult === true) {
        $_SESSION['user']->full_name = $fullname;
        $_SESSION['user']->user_name = $username;
        $_SESSION['user']->email = $email;
        $_SESSION['user']->bio = $bio;

        if ($avatarPath !== null) {
            $_SESSION['user']->avatar = $avatarPath;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công!',
            'avatar'  => $avatarPath
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => is_string($updateResult)
                ? $updateResult
                : 'Cập nhật thất bại, vui lòng thử lại.'
        ]);
    }

    exit;
}

}