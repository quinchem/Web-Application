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
