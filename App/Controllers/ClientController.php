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

    // 1. Hàm hiển thị giao diện tổng lần đầu cho Client
    public function index() {
        // Gọi chính xác file layout tổng nằm trong thư mục Client/Profile
        include __DIR__ . '/../Views/Client/Profile/Index.php';
    }
    
    // 2. Hàm xử lý yêu cầu đổi mục từ Ajax ngầm
    public function loadSubPage() {
        $action = $_GET['action'] ?? 'edit';
        
        // Ánh xạ chính xác đến các file view tương ứng của Client
        $views = [
            'edit'            => 'Profile/edit.php',
            'change_password' => 'Profile/change_password.php',
            'saved'           => 'Post/saved.php',
            'my_posts'        => 'Post/my_posts.php'
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
}
?>