<?php

require_once __DIR__ .
'/../App/Controllers/DashboardController.php';

use App\Controllers\DashboardController;

$router->get(

    'dashboard',

    [DashboardController::class, 'index']
);


$router->post(

    'dashboard_ajax',

    [DashboardController::class, 'loadDashboardAjax']
);