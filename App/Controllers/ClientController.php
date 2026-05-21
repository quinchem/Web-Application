<?php
// App/Controllers/ClientController.php 

class ClientController {

    private $clientRepository;

    // Hàm khởi tạo sẽ tự động chạy đầu tiên khi Controller được gọi
    public function __construct() {
        // Nhúng file ClientRepository vào
        require_once __DIR__ . '/../../Repositories/ClientRepository.php';
        
        // Khởi tạo đối tượng gán vào biến
        $this->clientRepository = new ClientRepository();
    }

    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['user_name']);
            $password = $_POST['password'];

            $user = $this->clientRepository->getUserByUsername($username);

            // LẤY ĐƯỜNG DẪN TRANG TRƯỚC ĐÓ (Nơi người dùng vừa đứng để bấm Submit)
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=homepage';

            if ($user && $user['account_status'] === 'active' && $user['role_id'] === 'RL0002' && $password === $user['password']) {
                
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['full_name'] = $user['full_name']; 
                $_SESSION['avatar'] = $user['avatar'];
                
                $_SESSION['success_msg'] = "Đăng nhập thành công!";

                // KIỂM TRA ĐIỀU HƯỚNG KHI THÀNH CÔNG:
                // Nếu họ đăng nhập từ trang Login riêng -> Đẩy về trang chủ
                // Nếu họ đăng nhập từ Modal (ở bài viết, danh mục...) -> Ở lại trang hiện tại
                if (strpos($referer, 'page=login') !== false) {
                    header('Location: index.php?page=homepage');
                } else {
                    header('Location: ' . $referer); 
                }
                exit(); 
                
            } else {
                // Đăng nhập thất bại
                $_SESSION['error'] = 'Thông tin đăng nhập không chính xác hoặc tài khoản không hợp lệ.';
                
                // ĐIỀU HƯỚNG KHI THẤT BẠI:
                // Sai ở đâu thì load lại y xì trang đó để báo lỗi
                header('Location: ' . $referer);
                exit();
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=homepage');
        exit();
    }
}
?>