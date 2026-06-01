<?php
// Routes/Post.php

use App\Controllers\PostController;

// Định tuyến cho các hành động liên quan đến bài viết
// Định tuyến cho trang chủ
$router->get('homepage', [PostController::class, 'homepage']);

// Định tuyến cho tìm kiếm bài viết
$router->get('search_result', [PostController::class, 'searchResult']);

// Định tuyến cho các hành động like, save, comment
$router->get('api_like', [PostController::class, 'api_like']);
$router->post('api_like', [PostController::class, 'api_like']);
$router->get('api_save', [PostController::class, 'api_save']);
$router->post('api_save', [PostController::class, 'api_save']);
$router->get('api_comment', [PostController::class, 'apiAddComment']);
$router->post('api_comment', [PostController::class, 'apiAddComment']);
$router->post('api_delete_comment', [PostController::class, 'apiDeleteComment']);

// Định tuyến cho trang chi tiết bài viết
$router->get('post', [PostController::class, 'post']);
// Định tuyến cho API lấy danh sách bình luận
$router->get('api_get_comments', [PostController::class, 'apiGetComments']);
$router->post('api_get_comments', [PostController::class, 'apiGetComments']);

// Định tuyến cho trang lưu bài viết và trang bài viết của tôi
$router->get('client_saved_posts_page', [PostController::class, 'savedPosts']);
$router->get('client_my_posts_page', [PostController::class, 'myPostsPage']);

// Định tuyến cho tạo bài viết
$router->get('create_post', [PostController::class, 'clientCreatePostPage']);
$router->post('client_store_post', [PostController::class, 'clientStorePost']);

// Định tuyến cho trang chi tiết danh mục và danh mục con
$router->get('category_detail', [PostController::class, 'categoryDetail']);
$router->get('subcategory', [PostController::class, 'subCategoryDetail']);

// Định tuyến cho chỉnh sửa bài viết
$router->get('client_edit_post', [PostController::class, 'clientEditPostPage']);

// Định tuyến cho xóa bài viết
$router->post('client_delete_post', [PostController::class, 'clientDeletePost']);

// Định tuyến cho API tải ảnh lên
$router->post('api_upload_image', [PostController::class, 'apiUploadImage']);