<?php
// Routes/Admin.php

use App\Controllers\PostController;
// use App\Controllers\CategoryController; // Nếu bạn có các Controller khác

// 2. Các trang hiển thị danh mục và chi tiết bài đọc công khai
$router->get('category', [PostController::class, 'category']);
$router->get('post', [PostController::class, 'post']);
$router->get('hide_post', [PostController::class, 'hidePost']);

// 4. Luồng xử lý duyệt bài đăng hệ thống dành cho Admin
$router->get('admin_user_posts', [PostController::class, 'adminUserPosts']);
$router->get('review_post', [PostController::class, 'reviewPost']);
