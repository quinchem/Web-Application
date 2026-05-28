<?php
// App/Controllers/DashboardController.php

namespace App\Controllers;

require_once __DIR__ .
'/../../Repositories/DashboardRepository.php';

class DashboardController
{
    private $repo;

    public function __construct()
    {
        $this->repo =
        new \DashboardRepository();
    }

    // =========================
    // VIEW DASHBOARD
    // =========================

    public function index()
    {
        $categories =
        $this->repo->getCategories();

        require_once __DIR__ .
        '/../Views/Admin/Dashboard/Dashboard_Index.php';
    }

    // =========================
    // AJAX LOAD DASHBOARD
    // =========================

    public function loadDashboardAjax()
    {
        header('Content-Type: application/json');

        $fromDate =
        $_POST['fromDate'] ?? null;

        $toDate =
        $_POST['toDate'] ?? null;

        $category =
        $_POST['category'] ?? null;

        $author =
        $_POST['author'] ?? null;

        $data = [

            'kpi' => $this->repo->getKPI(
                $fromDate,
                $toDate,
                $category,
                $author
            ),

            'chart' => $this->repo->getPostChart(
                $fromDate,
                $toDate,
                $category,
                $author
            ),

            'status' => $this->repo->getStatusChart(
                $fromDate,
                $toDate,
                $category,
                $author
            ),

            'topPosts' => $this->repo->getTopPosts(
                $fromDate,
                $toDate,
                $category,
                $author
            )
        ];

        echo json_encode($data);

        exit();
    }
}