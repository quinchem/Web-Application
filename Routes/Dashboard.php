<?php

require_once __DIR__ .
'/../App/Controllers/DashboardController.php';

use App\Controllers\DashboardController;

// Định tuyến cho trang dashboard Admin
$router->get(

    'dashboard',

    [DashboardController::class, 'index']
);


$router->post(

    'dashboard_ajax',

    [DashboardController::class, 'loadDashboardAjax']
);