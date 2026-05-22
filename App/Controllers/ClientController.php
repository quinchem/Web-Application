<?php
// App/Controllers/ClientController.php 
namespace App\Controllers;
class ClientController
{

    private $clientRepository;

    // Hàm khởi tạo sẽ tự động chạy đầu tiên khi Controller được gọi
    public function __construct()
    {
        // Nhúng file ClientRepository vào
        require_once __DIR__ . '/../../Repositories/ClientRepository.php';

        // Khởi tạo đối tượng gán vào biến
        $this->clientRepository = new \ClientRepository();
    }

    public function loginForm()
    {
        require_once __DIR__ . '/../Views/Client/Auth/Login.php';
        exit();
    }

    public function loginProcess()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['user_name']);
        $password = $_POST['password'];

        $user = $this->clientRepository->getUserByUsername($username);

        // LẤY ĐƯỜNG DẪN TRANG TRƯỚC ĐÓ (Nơi người dùng vừa đứng để bấm Submit)
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=homepage';

        // 1. LỚP SÀN LỌC 1: Kiểm tra xem User có tồn tại, tài khoản đang Active và đúng quyền Client (RL0002) không
        if ($user && $user['account_status'] === 'active' && $user['role_id'] === 'RL0002') {
            
            // 2. LỚP SÀN LỌC 2: Kiểm tra mật khẩu (Hỗ trợ password_verify và cả mật khẩu thường khi dev)
            $isPasswordCorrect = false;
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $isPasswordCorrect = true;
            }

            // Nếu mật khẩu hoàn toàn chính xác
            if ($isPasswordCorrect) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['avatar'] = $user['avatar'];

                $_SESSION['success_msg'] = "Đăng nhập thành công!";

                // Lưu tạm đường link cần chuyển tới vào session
                $_SESSION['redirect_url'] = (strpos($referer, 'page=login') !== false) ? 'index.php?page=homepage' : $referer;

                // BẮT BUỘC CHUYỂN VỀ TRANG LOGIN ĐỂ HIỆN THÔNG BÁO TRƯỚC
                header('Location: index.php?page=login');
                exit();
            }
        }

        // 3. ĐĂNG NHẬP THẤT BẠI: Sẽ nhảy xuống đây nếu sai Username, sai trạng thái, sai quyền hoặc gõ sai mật khẩu
        $_SESSION['error'] = 'Thông tin đăng nhập không chính xác hoặc tài khoản không hợp lệ.';

        // ĐIỀU HƯỚNG KHI THẤT BẠI:
        header('Location: ' . $referer);
        exit();
    }
}

    public function logout()
    {
        session_destroy();
        header('Location: index.php?page=homepage');
        exit();
    }

    // 1. Hàm hiển thị giao diện tổng lần đầu cho Client
    public function index()
    {
        // Gọi chính xác file layout tổng nằm trong thư mục Client/Profile
        include __DIR__ . '/../Views/Client/Profile/Index.php';
    }

    // 2. Hàm xử lý yêu cầu đổi mục từ Ajax ngầm
    public function loadSubPage()
    {
        $action = $_GET['action'] ?? 'edit';

        // Ánh xạ chính xác đến các file view tương ứng của Client
        $views = [
            'edit' => 'Profile/edit.php',
            'change_password' => 'Profile/change_password.php',
            'saved' => 'Post/saved.php',
            'my_posts' => 'Post/my_posts.php'
        ];

        if (array_key_exists($action, $views)) {
            $file_path = __DIR__ . '/../Views/Client/' . $views[$action];

            if (file_exists($file_path)) {
                // Trả về duy nhất đoạn mã HTML con cho JavaScript tiếp nhận
                include $file_path;
            } else {
                echo "<div class='alert alert-danger m-0'>Lỗi: Không tìm thấy file thành phần.</div>";
            }
        } else {
            echo "<div class='alert alert-danger m-0'>Yêu cầu không hợp lệ.</div>";
        }
        exit; // Ngắt để không bị dính các mã giao diện thừa bên ngoài
    }

    // Bổ sung vào bên trong Class ClientController trong file App/Controllers/ClientController.php

   public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId   = $_SESSION['user_id'] ?? null;
            $username = trim($_POST['username'] ?? ''); 
            $fullName = trim($_POST['fullname'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $gender   = $_POST['gender'] ?? 'female';

            if (!$userId) {
                header('Location: index.php?page=login');
                exit();
            }

            $result = $this->clientRepository->updateProfile($userId, $username, $fullName, $gender);

            if ($result) {
                // Đồng bộ cập nhật ngay lập tức lên Header và Sidebar toàn hệ thống
                $_SESSION['user_name']   = $username;
                $_SESSION['full_name']   = $fullName;
                $_SESSION['success_msg'] = "Cập nhật hồ sơ cá nhân thành công!";
            } else {
                $_SESSION['error_msg']   = "Lỗi: Không thể lưu thay đổi vào cơ sở dữ liệu.";
            }

            // Chuyển hướng reload lại trang cá nhân
            header('Location: index.php?page=client_profile&tab=edit');
            exit();
        }
    }
    /**
 * Tiếp nhận và xử lý logic đổi mật khẩu mới (POST)
 */
public function changePasswordProcess() 
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Lấy thông tin định danh từ Session và dữ liệu thô từ Form
        $userId     = $_SESSION['user_id'] ?? null;
        $currentPwd = $_POST['current_password'] ?? '';
        $newPwd     = $_POST['new_password'] ?? '';
        $confirmPwd = $_POST['confirm_password'] ?? '';

        if (!$userId) {
            header('Location: index.php?page=login');
            exit();
        }

        // 2. Kiểm tra xem mật khẩu mới và ô nhập lại có khớp nhau không
        if ($newPwd !== $confirmPwd) {
            $_SESSION['error_msg'] = "Mật khẩu mới và xác nhận mật khẩu không trùng khớp!";
            header('Location: index.php?page=client_profile&tab=change_password');
            exit();
        }

        // 3. Lấy dữ liệu tài khoản tươi từ DB để kiểm tra mật khẩu hiện tại
        $username = $_SESSION['user_name'] ?? '';
        $user = $this->clientRepository->getUserByUsername($username);

        if (!$user) {
            $_SESSION['error_msg'] = "Không tìm thấy thông tin tài khoản!";
            header('Location: index.php?page=client_profile&tab=change_password');
            exit();
        }

        // 4. KIỂM TRA MẬT KHẨU CŨ: Giải mã chuỗi hash trong DB bằng password_verify
        // (Hàm này tự động khớp muối salt, an toàn tuyệt đối)
       $isPasswordCorrect = false;

// Nếu mật khẩu gõ vào trùng khít với DB (chữ thường) HOẶC khớp mã hóa hash
if ($currentPwd === $user['password'] || password_verify($currentPwd, $user['password'])) {
    $isPasswordCorrect = true;
}

if (!$isPasswordCorrect) {
    $_SESSION['error_msg'] = "Mật khẩu hiện tại không chính xác!";
    header('Location: index.php?page=client_profile&tab=change_password');
    exit();
}

        // 5. MÃ HÓA MẬT KHẨU MỚI: Sử dụng thuật toán mã hóa mạnh mẽ nhất mặc định của PHP
        $hashedNewPassword = password_hash($newPwd, PASSWORD_DEFAULT);

        // 6. Gọi Repository ghi dữ liệu mới xuống bảng user
        $result = $this->clientRepository->updatePassword($userId, $hashedNewPassword);

        if ($result) {
            $_SESSION['success_msg'] = "Thay đổi mật khẩu tài khoản thành công!";
        } else {
            $_SESSION['error_msg'] = "Lỗi hệ thống: Không thể cập nhật dữ liệu mật khẩu.";
        }

        // Chuyển hướng reload lại giao diện trang cá nhân
        header('Location: index.php?page=client_profile&tab=change_password');
        exit();
    }
}
}

?>