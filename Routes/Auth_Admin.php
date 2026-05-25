<?php

require_once __DIR__ .
'/../App/Controllers/AdminController.php';


// =====================================================
// LOGIN
// =====================================================

$router->get(
    'admin_login',
    [AdminController::class, 'login']
);

$router->post(
    'admin_login',
    [AdminController::class, 'handleLogin']
);


// =====================================================
// FORGOT PASSWORD
// =====================================================

$router->get(
    'forgot_password',
    [AdminController::class, 'forgotPassword']
);

$router->post(
    'handle_forgot_password',
    [AdminController::class, 'handleForgotPassword']
);


// =====================================================
// RESET PASSWORD
// =====================================================

$router->get(
    'admin_reset_password',
    [AdminController::class, 'resetPassword']
);

$router->post(
    'admin_reset_password_ajax',
    [AdminController::class, 'resetPasswordAjax']
);


// =====================================================
// DASHBOARD
// =====================================================

$router->get(
    'admin_dashboard',
    [AdminController::class, 'dashboard']
);


// =====================================================
// LOGOUT
// =====================================================

$router->get(
    'logout',
    [AdminController::class, 'logout']
);