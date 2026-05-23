<?php
// Routes/Auth.php

use App\Controllers\AuthController;

// Đăng ký luồng hiển thị trang đăng nhập độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get('login', [AuthController::class, 'loginForm']); 
$router->post('login', [AuthController::class, 'loginProcess']); 

// Luồng xử lý Đăng xuất tài khoản công khai
$router->get('logout', [AuthController::class, 'logout']);
