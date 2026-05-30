<?php

// ======================================================
// LOAD ENV
// ======================================================

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file(
        $filePath,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    foreach ($lines as $line) {

        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {

            list($key, $value) =
            explode('=', $line, 2);

            $_ENV[trim($key)] =
            trim($value);
        }
    }
}

loadEnv(__DIR__ . '/.env');


// ======================================================
// AUTOLOAD — ✅ PHẢI Ở ĐÂY, TRƯỚC MỌI THỨ
// ======================================================

require_once __DIR__ . '/vendor/autoload.php';


// ======================================================
// START SESSION
// ======================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ======================================================
// AUTOLOAD CLASS
// ======================================================

spl_autoload_register(function ($class) {

    $file =
    __DIR__ . '/' .
    str_replace('\\', '/', $class) .
    '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


// ======================================================
// ROUTER
// ======================================================

require_once __DIR__ .
'/Routes/AdminRouter.php';

$router = new AdminRouter();


// ======================================================
// LOAD ROUTES
// ======================================================

require_once __DIR__ .
'/Routes/Auth_Admin.php';

require_once __DIR__ .
'/Routes/Admin.php';

require_once __DIR__ .
'/Routes/Profile.php';

require_once __DIR__ .
'/Routes/Dashboard.php';
// ======================================================
// PAGE HIỆN TẠI
// ======================================================

$page =
$_GET['page'] ?? 'admin_login';


// ======================================================
// AUTO LOGIN
// ======================================================

if (

    !isset($_SESSION['user'])

    &&

    isset($_COOKIE['remember_token'])

) {

    require_once __DIR__ .
    '/Repositories/ClientRepository.php';

    $clientRepository =
    new ClientRepository();

    $user =
    $clientRepository
    ->findByRememberToken(
        $_COOKIE['remember_token']
    );

    if ($user && $user->role_id === 'RL0001') {

        $_SESSION['user'] = $user;

        header(
        "Location: http://localhost/Web-Application/Admin_index.php?page=dashboard"
        );

        exit();
        } else {
        // Token không hợp lệ → xóa cookie đi
        setcookie('remember_token', '', time() - 3600, '/');
    }
}


// ======================================================
// PUBLIC PAGES
// ======================================================

$publicPages = [
    'admin_login',
    'forgot_password',
    'handle_forgot_password',
    'admin_reset_password',
    'admin_reset_password_ajax'
];


// ======================================================
// SECURITY CHECK
// ======================================================

if (

    !isset($_SESSION['user'])

    &&

    !in_array($page, $publicPages)

) {

    header(
    "Location: http://localhost/Web-Application/Admin_index.php?page=admin_login"
    );

    exit();
}


// ======================================================
// RESOLVE ROUTE
// ======================================================

$router->resolve($_SERVER['REQUEST_METHOD']);
