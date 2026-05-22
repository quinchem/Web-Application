<?php
/**
 * Routes/Profile.php
 * Xử lý định tuyến cho các trang liên quan đến tài khoản cá nhân
 */

require_once __DIR__ . '/../App/Controllers/ProfileController.php';

$profileController = new ProfileController();

// Lấy tham số page từ URL. Nếu không có thì gán mặc định là 'profile'
// Dòng này giúp giải quyết lỗi "Undefined variable '$page'" của VS Code
$page = $_GET['page'] ?? 'profile'; 

switch ($page) {
    case 'profile':
        // Trang thông tin tài khoản (nếu có)
        // $profileController->index();
        break;

    case 'change_password':
        // Nếu là method POST (nhấn nút Cập nhật)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileController->updatePassword();
        } else {
            // Nếu là method GET (chỉ hiển thị trang)
            $profileController->changePassword();
        }
        break;

    // ... các case khác của profile (ví dụ: edit_profile)
    
    default:
        // Xử lý khi nhập sai page
        echo "<h1>404 - Không tìm thấy trang</h1>";
        break;
}