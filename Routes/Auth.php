<?php
require_once __DIR__ . '/../App/Controllers/ClientController.php';

$clientController = new ClientController();
$method = $_SERVER['REQUEST_METHOD'];

// Gọi biến $page từ file index.php
global $page;

// Kiểm tra nếu người dùng đang ở trang đăng nhập
if ($page === 'login') {
    if ($method === 'POST') {
        // Xử lý gửi form đăng nhập
        $clientController->loginProcess(); 
    } else {
        // Hiển thị trang đăng nhập độc lập
        require_once __DIR__ . '/../App/Views/Client/Auth/Login.php';
        
        // BẮT BUỘC PHẢI CÓ DÒNG NÀY ĐỂ CHẶN KHÔNG CHO LOAD TIẾP TRANG CHỦ
        exit(); 
    }
}

// Xử lý đăng xuất
if ($page === 'logout') {
    $clientController->logout();
    exit(); // Thêm vào đây luôn cho an toàn tuyệt đối
}
?>