<?php
namespace App\Controllers;
/**
 * PostController.php
 * Controller xử lý các yêu cầu liên quan đến bài viết (post)
 * Quản lý việc lấy dữ liệu từ database và truyền đến view
 */

require_once __DIR__ . '/../../Repositories/PostRepository.php';
require_once __DIR__ . '/../../Repositories/CategoryRepository.php';


/**
 * Lớp PostController
 * Xử lý logic chính cho trang web phía client liên quan đến bài viết
 */
class PostController
{
    private $postRepository;        // Repository để lấy dữ liệu bài viết

    /**
     * Constructor - Khởi tạo các repository
     */
    public function __construct()
    {
        $this->postRepository = new \PostRepository();
    }

    /**
     * Hiển thị trang chủ với bài viết nổi bật và các danh mục
     */
    public function homepage()
    {
        // Lấy bài viết bao gồm cả các danh mục con
        $thoiSu = $this->postRepository->getPostsByParentCategory('Thời sự', 4);
        $kinhTe = $this->postRepository->getPostsByParentCategory('Kinh tế', 4);
        $heroPost = $this->postRepository->getHeroPost();

        require __DIR__ . '/../Views/Client/Home.php';
    }

    /**
     * Hiển thị danh sách bài viết của một danh mục cụ thể
     * (ĐÃ CẬP NHẬT: Tự động lấy id từ URL)
     */
    public function category()
    {
        $categoryId = $_GET['id'] ?? null;
        if (!$categoryId) {
            $this->homepage();
            return;
        }

        // Lấy danh sách bài viết theo ID danh mục
        $posts = $this->postRepository->getPostsByParentCategory($categoryId);
        require __DIR__ . '/../Views/Client/Category/Detail.php';
    }

    /**
     * Hiển thị chi tiết một bài viết cụ thể
     * (ĐÃ CẬP NHẬT: Tự động lấy id từ URL)
     */
   

    public function hidePost()
    {
        if (!isset($_GET['id'])) {
            header('Location: Admin_index.php?page=admin_user_posts');
            exit;
        }

        $postId = $_GET['id'];
        $this->postRepository->hidePost($postId);

        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    public function reviewPost()
    {
        if (!isset($_GET['id'])) {
            header('Location: Admin_index.php?page=admin_user_posts');
            exit;
        }

        $postId   = $_GET['id'];
        $decision = $_GET['decision'] ?? 'approved';
        $reason   = urldecode($_GET['reason'] ?? '');

        // Chỉ cho phép 2 giá trị hợp lệ
        if (!in_array($decision, ['approved', 'rejected'])) {
            header('Location: Admin_index.php?page=admin_user_posts');
            exit;
        }

        $this->postRepository->reviewPost($postId, $decision, $reason);

        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    // ==========================================
    // CÁC HÀM XỬ LÝ AJAX (THÍCH, LƯU, BÌNH LUẬN)
    // ==========================================

    private function getCurrentUserId() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        return $_SESSION['user_id'] ?? null;
    }

    public function apiToggleLike() {
        $userId = $this->getCurrentUserId();
        if (!$userId) { 
            echo json_encode(['status' => 'unauthorized', 'message' => 'Vui lòng đăng nhập.']); 
            return; 
        }

        $postId = $_POST['post_id'] ?? null;
        if($postId) {
            echo json_encode($this->postRepository->toggleLike($postId, $userId));
        }
    }

    public function apiToggleSave() {
    $userId = $this->getCurrentUserId();

    if (!$userId) { 
        $this->jsonResponse([
            'status' => 'unauthorized', 
            'message' => 'Vui lòng đăng nhập.'
        ]);
    }

    $postId = $_POST['post_id'] ?? null;

    if (!$postId) {
        $this->jsonResponse([
            'status' => 'error', 
            'message' => 'Thiếu post_id'
        ]);
    }

    $this->jsonResponse(
        $this->postRepository->toggleBookmark($postId, $userId)
    );
}


    public function apiAddComment() {
        $userId = $this->getCurrentUserId();
        if (!$userId) { 
            echo json_encode(['status' => 'unauthorized', 'message' => 'Vui lòng đăng nhập.']); 
            return; 
        }

        $postId = $_POST['post_id'] ?? null;
        $content = $_POST['content'] ?? null;
        
        if($postId && $content) {
            if($this->postRepository->addComment($postId, $userId, $content)) {
                // Trả về dữ liệu để AJAX tự động vẽ lên màn hình mà không cần reload
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Đã gửi bình luận',
                    'comment' => [
                        'full_name' => $_SESSION['full_name'] ?? $_SESSION['user_name'],
                        'avatar' => $_SESSION['avatar'] ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                        'content' => nl2br(htmlspecialchars($content)),
                        'created_at' => date('d/m/Y H:i') // Lấy giờ hiện tại
                    ]
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi gửi bình luận']);
            }
        }
    }
    public function post()
{
    $postId = $_GET['id'] ?? null;
    if (!$postId) {
        $this->homepage();
        return;
    }

    // Lấy user hiện tại
    $userId = $this->getCurrentUserId();

    // 1. Khởi tạo biến mặc định để View không bị lỗi
    $post = null; 
    $tags = []; 
    $recommendedPosts = []; 
    $comments = [];
    $totalComments = 0; 
    $totalPages = 1;
    $isSaved = false;

    $page = isset($_GET['cpage']) ? max(1, (int)$_GET['cpage']) : 1;
    $limit = 5; 
    $offset = ($page - 1) * $limit;

    // 2. Lấy thông tin bài viết
    $post = $this->postRepository->getPostById($postId);

    if (!$post) {
        echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2>Bài viết không tồn tại hoặc đã bị ẩn!</h2>
                <a href='Index.php'>Về trang chủ</a>
              </div>";
        exit;
    }

    // 3. Kiểm tra bài viết đã được user lưu chưa
    if ($userId) {
        $isSaved = $this->postRepository->isBookmarked($postId, $userId);
    }

    // 4. Lấy dữ liệu bổ sung
    $tags = $this->postRepository->getPostTags($postId);
    $recommendedPosts = $this->postRepository->getRecommendedPosts($postId);
    
    // 5. Tính toán và lấy bình luận
    $totalComments = $this->postRepository->countTotalCommentsByPostId($postId);

    if ($totalComments > 0) {
        $comments = $this->postRepository->getCommentsByPostId($postId, $limit, $offset);
        $totalPages = ceil($totalComments / $limit);
    }

    // 6. Gọi View
    require __DIR__ . '/../Views/Client/Post/Detail.php';
}

public function savedPosts()
{
     if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        echo "<div class='saved-empty'><p class='mb-0'>Vui lòng đăng nhập để xem bài viết đã lưu.</p></div>";
        exit;
    }

    $savedLimit = 3;
    $savedCurrentPage = isset($_GET['saved_page']) ? (int)$_GET['saved_page'] : 1;

    if ($savedCurrentPage < 1) {
        $savedCurrentPage = 1;
    }

    $savedOffset = ($savedCurrentPage - 1) * $savedLimit;

    $savedTotalPosts = $this->postRepository->countSavedPostsByUser($userId);
    $savedTotalPages = (int)ceil($savedTotalPosts / $savedLimit);

    if ($savedTotalPages > 0 && $savedCurrentPage > $savedTotalPages) {
        $savedCurrentPage = $savedTotalPages;
        $savedOffset = ($savedCurrentPage - 1) * $savedLimit;
    }

    $savedPosts = $this->postRepository->getSavedPostsByUser(
        $userId,
        $savedLimit,
        $savedOffset
    );

    include __DIR__ . '/../Views/Client/Post/saved.php';
    exit;
}

    /**
     * Admin quản lý bài viết người đọc
     */
    public function adminUserPosts()
    {
        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'author_id'   => $_GET['author_id'] ?? '',
            'status'      => $_GET['status'] ?? '',
            'date'        => $_GET['date'] ?? ''
        ];

        $perPage     = 10;
        $currentPage = max(1, (int)($_GET['p'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;

        $posts      = $this->postRepository->getUserPostsForAdmin($filters, $perPage, $offset);
        $categories = $this->postRepository->getCategoriesForFilter();
        $authors    = $this->postRepository->getAuthorsForFilter();

        $totalPosts = $this->postRepository->countUserPosts();              // stat card: tất cả
        $totalForPages = $this->postRepository->countUserPostsFiltered($filters); // pagination: theo filter
        $pendingPosts  = $this->postRepository->countUserPostsByStatus('pending');
        $hiddenPosts   = $this->postRepository->countUserPostsByStatus('hidden');
        $trendingPosts = $this->postRepository->countTrendingUserPosts();

        $totalPages = (int)ceil($totalForPages / $perPage);

        require_once __DIR__ . '/../Views/Admin/Post/Index.php';
    }

    /**
     * Quản lý bài viết cho admin 
     */
    public function adminPosts()
{
    $filters = [
        'keyword'     => $_GET['keyword'] ?? '',
        'category_id' => $_GET['category_id'] ?? '',
        'status'      => $_GET['status'] ?? '',
        'date'        => $_GET['date'] ?? ''
    ];

    $perPage     = 10;
    $currentPage = max(1, (int)($_GET['p'] ?? 1));
    $offset      = ($currentPage - 1) * $perPage;

    $posts         = $this->postRepository->getAdminPosts($filters, $perPage, $offset);
    $totalPosts    = $this->postRepository->countAdminPosts();
    $totalForPages = $this->postRepository->countAdminPostsFiltered($filters);
    $hiddenPosts   = $this->postRepository->countAdminPostsByStatus('hidden');
    $trendingPosts = $this->postRepository->countTrendingAdminPosts();
    $totalPages    = (int)ceil($totalForPages / $perPage);

    require_once __DIR__ . '/../Views/Admin/Post/IndexAdmin.php';
}
}