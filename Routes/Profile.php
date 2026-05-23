<?php
// Routes/Profile.php

use App\Controllers\ProfileController;

// Nếu bạn vẫn dùng song song cho các hành động form cũ
$router->get('profile', [ProfileController::class, 'index']);
$router->post('update-profile',  [ProfileController::class, 'updateProfile']);
$router->post('change_password', [ProfileController::class, 'changePassword']);