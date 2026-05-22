<?php
// Index.php (Thư mục gốc)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Tự động nạp file Class theo Namespace (Autoload)
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 2. Khởi tạo đối tượng Router
require_once __DIR__ . '/Routes/Router.php';
$router = new Router();

// 3. Nạp cấu hình các file định tuyến con (Chỉ làm nhiệm vụ đăng ký cấu hình)
require_once __DIR__ . '/Routes/Auth.php';  
require_once __DIR__ . '/Routes/Profile.php';
require_once __DIR__ . '/Routes/Post.php';
require_once __DIR__ . '/Routes/Client.php'; 

// 4. KÍCH HOẠT ĐIỀU HƯỚNG DỰA TRÊN PHƯƠNG THỨC REQUEST
$requestMethod = $_SERVER['REQUEST_METHOD']; 
$router->resolve($requestMethod);
