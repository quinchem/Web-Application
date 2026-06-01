<?php
// Routes/Profile.php

use App\Controllers\ProfileController;

// Định tuyến cho trang hồ sơ cá nhân
$router->get('profile', [ProfileController::class, 'index']);

// Định tuyến cho cập nhật thông tin cá nhân và đổi mật khẩu
$router->post('update-profile',  [ProfileController::class, 'updateProfile']);

// Định tuyến cho đổi mật khẩu
$router->post('change_password', [ProfileController::class, 'changePassword']);