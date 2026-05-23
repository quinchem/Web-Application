<?php
// 1. Hàm tự viết để đọc file .env
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return; // Nếu không có file .env thì bỏ qua
    }
    
    // Đọc từng dòng trong file .env
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Bỏ qua các dòng chú thích (bắt đầu bằng dấu #)
        if (strpos(trim($line), '#') === 0) continue;
        
        // Tách biến và giá trị qua dấu '='
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Đẩy vào biến siêu toàn cục $_ENV của PHP
            $_ENV[$key] = $value;
        }
    }
}

// 2. Kích hoạt hàm và trỏ tới file .env đang nằm cùng thư mục gốc
loadEnv(__DIR__ . '/.env');

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
