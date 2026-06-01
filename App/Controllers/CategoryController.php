<?php
// App/Controllers/CategoryController.php

namespace App\Controllers;

require_once __DIR__ . '/../../Repositories/CategoryRepository.php';

// Controller để xử lý các yêu cầu liên quan đến danh mục
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