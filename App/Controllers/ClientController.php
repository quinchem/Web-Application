<?php
// App/Controllers/ClientController.php 
namespace App\Controllers;
class ClientController {

    private $clientRepository;
    public function __construct() {
        // Nhúng file ClientRepository vào
        require_once __DIR__ . '/../../Repositories/ClientRepository.php';
        
        // Khởi tạo đối tượng gán vào biến
        $this->clientRepository = new \ClientRepository();
    }


    public function index() {
        include __DIR__ . '/../Views/Client/Profile/Index.php';
    }
    
    public function loadSubPage() {
        $action = $_GET['action'] ?? 'edit';
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