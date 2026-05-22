<?php
// Routes/Client.php

use App\Controllers\ClientController;

// Đăng ký Route hiển thị khung xương giao diện tài khoản chính (Index) khi nhấn tên trên Header
$router->get('client_profile', [ClientController::class, 'index']);

// Đăng ký tuyến tiếp nhận yêu cầu Ajax tải động các file con (edit, change_password,...)
$router->get('client_account_sub_page', [ClientController::class, 'loadSubPage']);

$router->post('handle_profile', [ClientController::class, 'updateProfile']);
$router->post('handle_change_password', [ClientController::class, 'changePasswordProcess']);
$router->get('logout', [ClientController::class, 'logout']);