<?php
// Routes/Post.php

use App\Controllers\PostController;

// 1. Tuyến đường nạp Trang chủ hệ thống bài viết mặc định
$router->get('homepage', [PostController::class, 'homepage']);



// 3. Các tuyến API tiếp nhận Ajax phản hồi tương tác (Cấu hình hỗ trợ cả GET/POST cho an toàn)
$router->get('api_like', [PostController::class, 'api_like']);
$router->post('api_like', [PostController::class, 'api_like']);

$router->get('api_save', [PostController::class, 'api_save']);
$router->post('api_save', [PostController::class, 'api_save']);

$router->get('api_comment', [PostController::class, 'apiAddComment']);
$router->post('api_comment', [PostController::class, 'apiAddComment']);
$router->post('api_delete_comment', [PostController::class, 'apiDeleteComment']);

$router->get('post', [PostController::class, 'post']);
$router->get('api_get_comments', [PostController::class, 'apiGetComments']);
$router->post('api_get_comments', [PostController::class, 'apiGetComments']);
$router->get('client_saved_posts_page', [PostController::class, 'savedPosts']);
$router->get('client_my_posts_page', [PostController::class, 'myPostsPage']);

$router->get('category_detail', [PostController::class, 'categoryDetail']);
$router->get('subcategory', [PostController::class, 'subCategoryDetail']);