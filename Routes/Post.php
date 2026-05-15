<?php
/**
 * Routes/Post.php
 * 
 * Xử lý định tuyến (routing) cho các trang liên quan đến bài viết
 * Kế từ Index.php: lấy biến $page và xử lý logic định tuyến
 */

require_once __DIR__ . '/../App/Controllers/PostController.php';

$postController = new PostController();

switch ($page) {
    // ==========================================
    // TRANG CHỦ (HOMEPAGE)
    // ==========================================
    case 'homepage':
        // Trang chủ - Hiển thị bài viết nổi bật và các danh mục
        $postController->homepage();
        break;

    // ==========================================
    // DANH MỤC BÀI VIẾT
    // ==========================================
    case 'category':
        // Hiển thị danh sách bài viết theo danh mục
        $categoryId = $_GET['id'] ?? null;
        if ($categoryId) {
            $postController->category($categoryId);
        } else {
            $postController->homepage();
        }
        break;

    // ==========================================
    // CHI TIẾT BÀI VIẾT
    // ==========================================
    case 'post':
        // Hiển thị chi tiết một bài viết
        $postId = $_GET['id'] ?? null;
        if ($postId) {
            $postController->post($postId);
        } else {
            $postController->homepage();
        }
        break;

    // ==========================================
    // TRANG CHO QUẢN TRỊ VIÊN (ADMIN)
    // ==========================================
    case 'admin_user_posts':
        // Danh sách bài viết của người dùng trong giao diện Admin
        $postController->adminUserPosts();
        break;

    // ==========================================
    // MẶC ĐỊNH
    // ==========================================
    default:
        // Nếu không tìm thấy, về trang chủ
        $postController->homepage();
        break;
}