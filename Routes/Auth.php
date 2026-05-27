<?php
// Routes/Auth.php

use App\Controllers\AuthController;

// Đăng ký luồng hiển thị trang đăng nhập độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get('login', [AuthController::class, 'loginForm']); 
$router->post('login', [AuthController::class, 'loginProcess']); 

// Luồng xử lý Đăng xuất tài khoản công khai
$router->get('logout', [AuthController::class, 'logout']);

// Đăng ký luồng hiển thị trang đăng ký độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get(
    'forgot-password',
    [AuthController::class, 'forgotPasswordForm']
);

$router->post(
    'forgot-password',
    [AuthController::class, 'forgotPasswordProcess']
);

// Đăng ký luồng hiển thị trang đặt lại mật khẩu độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get(
    'reset-password',
    [AuthController::class, 'resetPasswordForm']
);

$router->post(
    'reset-password',
    [AuthController::class, 'resetPasswordProcess']
);

// Đăng ký luồng hiển thị trang đăng ký độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get(
    'register',
    [AuthController::class, 'registerForm']
);

$router->post(
    'register',
    [AuthController::class, 'registerProcess']
);