<?php
/**
 * --------------------------------------------------------------------------
 * Web Application - Admin Entry Point (Cổng vào phân hệ Quản trị)
 * --------------------------------------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔥 BỔ SUNG: Nạp cấu hình file .env để PHP hiểu được các biến DB_HOST, DB_PASSWORD... của TiDB Cloud
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv(trim($name) . '=' . trim($value));
    }
}

// =========================================================================
// KHÔNG GIAN PHÂN QUYỀN TOÀN CỤC (GLOBAL SECURITY GATE)
// =========================================================================
// Giả sử mã quyền Admin của bạn là 'RL0001' (Dựa theo ClientController kiểm tra RL0002 là Client


// 1. Tự động nạp file Class theo Namespace (Autoload)
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 2. Khởi tạo đối tượng Router riêng cho Admin
require_once __DIR__ . '/Routes/AdminRouter.php';
$router = new AdminRouter();

// 3. CHỈ NẠP DUY NHẤT FILE ROUTES CỦA ADMIN (Bảo mật tuyệt đối, không lẫn lộn với Client)
require_once __DIR__ . '/Routes/Admin.php'; 
require_once __DIR__ . '/Routes/Profile.php'; 

// 4. Kích hoạt điều hướng cho Admin
$requestMethod = $_SERVER['REQUEST_METHOD']; 
$router->resolve($requestMethod);