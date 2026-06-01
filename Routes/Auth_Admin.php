<?php

require_once __DIR__ .'/../App/Controllers/AdminController.php';


// Định tuyến cho trang đăng nhập Admin
$router->get('admin_login',[AdminController::class, 'login']);
$router->post('admin_login',[AdminController::class, 'handleLogin']);

// Định tuyến cho trang quên mật khẩu 
$router->get('forgot_password', [AdminController::class, 'forgotPassword']);
$router->post('handle_forgot_password',[AdminController::class, 'handleForgotPassword']);


// Định tuyến cho trang đặt lại mật khẩu 
$router->get( 'admin_reset_password',[AdminController::class, 'resetPassword']);
$router->post('admin_reset_password_ajax',[AdminController::class, 'resetPasswordAjax']);

// Định tuyến cho trang dashboard Admin
$router->get( 'logout',[AdminController::class, 'logout']);