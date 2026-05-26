<?php
// App/Controllers/CategoryController.php

namespace App\Controllers;

require_once __DIR__ . '/../../Repositories/CategoryRepository.php';

class CategoryController
{
    private $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new \CategoryRepository();
    }

    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }
}