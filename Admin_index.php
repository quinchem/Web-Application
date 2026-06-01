<?php

// Load biến môi trường từ file .env
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

// Nạp autoload từ Composer
require_once __DIR__ . '/vendor/autoload.php';


// Khởi tạo session nếu chưa được khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Tự động nạp file Class theo Namespace (Autoload) cho Admin
spl_autoload_register(function ($class) {

    $file =
        __DIR__ . '/' .
        str_replace('\\', '/', $class) .
        '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


// Khởi tạo đối tượng Router cho Admin
require_once __DIR__ . '/Routes/AdminRouter.php';

$router = new AdminRouter();

// Nạp cấu hình các file định tuyến con 
require_once __DIR__ . '/Routes/Auth_Admin.php';

require_once __DIR__ .'/Routes/Admin.php';

require_once __DIR__ . '/Routes/Profile.php';

require_once __DIR__ . '/Routes/Dashboard.php';


// Phân tích tham số ?page=... để điều hướng
$page =
    $_GET['page'] ?? 'admin_login';



if (

    !isset($_SESSION['user'])

    &&

    isset($_COOKIE['remember_token'])

) {
    // Tự động đăng nhập nếu có cookie hợp lệ

    require_once __DIR__ .'/Repositories/ClientRepository.php';

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


// Các trang công khai mà không cần đăng nhập

$publicPages = [
    'admin_login',
    'forgot_password',
    'handle_forgot_password',
    'admin_reset_password',
    'admin_reset_password_ajax'
];


// Nếu chưa đăng nhập và truy cập vào trang không công khai → chuyển hướng về trang đăng nhập Admin

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



// Hàm điều hướng phân tích tham số ?page=...
$router->resolve($_SERVER['REQUEST_METHOD']);
