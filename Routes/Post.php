<?php

require_once __DIR__ . '/../App/Controllers/PostController.php';

$postController = new PostController();

switch ($page) {
    // ==========================================
    // TRANG CHO NGƯỜI DÙNG (USER)
    // ==========================================
    case 'home':
        $postController->homepage(); // Trang chủ Trạm Tin Việt
        break;

    // ==========================================
    // TRANG CHO QUẢN TRỊ VIÊN (ADMIN)
    // ==========================================
    case 'admin_user_posts':
        // Gọi hàm xử lý danh sách bài viết trong giao diện Admin
        $postController->adminUserPosts(); 
        break;
}