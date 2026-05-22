<?php
// Routes/Post.php

use App\Controllers\PostController;

// 1. Tuyến đường nạp Trang chủ hệ thống bài viết mặc định
$router->get('homepage', [PostController::class, 'homepage']);



// 3. Các tuyến API tiếp nhận Ajax phản hồi tương tác (Cấu hình hỗ trợ cả GET/POST cho an toàn)
$router->get('api_like', [PostController::class, 'apiToggleLike']);
$router->post('api_like', [PostController::class, 'apiToggleLike']);

$router->get('api_save', [PostController::class, 'apiToggleSave']);
$router->post('api_save', [PostController::class, 'apiToggleSave']);

$router->get('api_comment', [PostController::class, 'apiAddComment']);
$router->post('api_comment', [PostController::class, 'apiAddComment']);

$router->get('post', [PostController::class, 'post']);

