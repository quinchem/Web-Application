<?php
// Routes/Auth.php

use App\Controllers\ClientController;

// Đăng ký luồng hiển thị trang đăng nhập độc lập (GET) và xử lý gửi form dữ liệu (POST)
$router->get('login', [ClientController::class, 'loginForm']); 
$router->post('login', [ClientController::class, 'loginProcess']); 

// Luồng xử lý Đăng xuất tài khoản công khai
$router->get('logout', [ClientController::class, 'logout']);