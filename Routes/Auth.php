<?php


require_once __DIR__ .
'/../App/Controllers/AdminController.php';


$adminController = new AdminController();


$method = $_SERVER['REQUEST_METHOD'];


global $page;


// ========================================
// ADMIN LOGIN
// URL:
// Index.php?page=admin_login
// ========================================


if ($page === 'admin_login') {


    if ($method === 'POST') {


        $adminController->handleLogin();


    } else {


        require_once __DIR__ .
        '/../App/Views/Admin/Auth/Login.php';
    }


    exit();
}


// ========================================
// FORGOT PASSWORD
// URL:
// Index.php?page=forgot_password
// ========================================


if ($page === 'forgot_password') {


    require_once __DIR__ .
    '/../App/Views/Admin/Auth/Forgot_password.php';


    exit();
}

// ========================================
// RESET PASSWORD
// FORM ĐỔI MẬT KHẨU MỚI
//
// URL:
// Index.php?page=reset_password
// ========================================

if ($page === 'reset_password') {

    require_once __DIR__ .
    '/../App/Views/Admin/Auth/Reset_password.php';

    exit();
}



// ========================================
// AJAX RESET PASSWORD
//
// URL:
// Index.php?page=reset_password_ajax
// ========================================

if ($page === 'reset_password_ajax') {

    $adminController->resetPasswordAjax();

    exit();
}


// ========================================
// ADMIN DASHBOARD
// URL:
// Index.php?page=admin_dashboard
// ========================================


if ($page === 'admin_dashboard') {


    $adminController->dashboard();


    exit();
}




// ========================================
// LOGOUT
// URL:
// Index.php?page=logout
// ========================================


if ($page === 'logout') {


    $adminController->logout();


    exit();
}


?>
