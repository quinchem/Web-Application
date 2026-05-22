<?php
/**
 * --------------------------------------------------------------------------
 * Web Application - Admin Entry Point (Cổng vào phân hệ Quản trị)
 * --------------------------------------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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