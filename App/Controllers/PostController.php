<?php
/**
 * PostController.php
 * * Controller xử lý các yêu cầu liên quan đến bài viết (post)
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
    private $categoryRepository;    // Repository để lấy dữ liệu danh mục

    /**
     * Constructor - Khởi tạo các repository
     */
    public function __construct()
    {
        $this->postRepository = new PostRepository();
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
     */
    public function category($categoryId)
    {
        // Lấy danh sách bài viết theo ID danh mục
        $posts = $this->postRepository->getPostsByParentCategory($categoryId);
        require __DIR__ . '/../Views/Client/Category/Detail.php';
    }

    /**
     * Hiển thị chi tiết một bài viết cụ thể
     */
    public function post($postId)
    {
        // 1. Khởi tạo biến mặc định để View không bị lỗi
        $post = null; 
        $tags = []; 
        $recommendedPosts = []; 
        $comments = [];
        $totalComments = 0; 
        $totalPages = 1; 

        $page = isset($_GET['cpage']) ? max(1, (int)$_GET['cpage']) : 1;
        $limit = 5; 
        $offset = ($page - 1) * $limit;

        // 2. Lấy thông tin bài viết
        $post = $this->postRepository->getPostById($postId);
        if (!$post) {
            echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Bài viết không tồn tại hoặc đã bị ẩn!</h2><a href='Index.php'>Về trang chủ</a></div>";
            exit;
        }

        // 3. Lấy dữ liệu bổ sung
        $tags = $this->postRepository->getPostTags($postId);
        $recommendedPosts = $this->postRepository->getRecommendedPosts($postId);
        
        // 4. Tính toán và lấy bình luận
        $totalComments = $this->postRepository->countTotalCommentsByPostId($postId);
        if ($totalComments > 0) {
            $comments = $this->postRepository->getCommentsByPostId($postId, $limit, $offset);
            $totalPages = ceil($totalComments / $limit);
        }

        // 5. Gọi View
        require __DIR__ . '/../Views/Client/Post/Detail.php';
    }

    /**
     * Hiển thị danh sách bài viết cho admin
     */
    public function adminUserPosts()
    {
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'author_id' => $_GET['author_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'date' => $_GET['date'] ?? ''
        ];

        $posts = $this->postRepository->getUserPostsForAdmin($filters);
        $categories = $this->postRepository->getCategoriesForFilter();
        $authors = $this->postRepository->getAuthorsForFilter();

        $totalPosts = $this->postRepository->countUserPosts();
        $pendingPosts = $this->postRepository->countUserPostsByStatus('pending');
        $hiddenPosts = $this->postRepository->countUserPostsByStatus('hidden');
        $trendingPosts = $this->postRepository->countTrendingUserPosts();

        require_once __DIR__ . '/../Views/Admin/Post/Index.php';
    }

    public function hidePost()
    {
        if (!isset($_GET['id'])) {
            header('Location: Index.php?page=admin_user_posts');
            exit;
        }

        $postId = $_GET['id'];
        $this->postRepository->hidePost($postId);

        header('Location: Index.php?page=admin_user_posts');
        exit;
    }

    // ==========================================
    // CÁC HÀM XỬ LÝ AJAX (THÍCH, LƯU, BÌNH LUẬN)
    // ==========================================

    // ID người dùng giả lập. BẠN HÃY SỬA SỐ '1' THÀNH ID CÓ THẬT TRONG BẢNG User CỦA BẠN.
    private $fakeUserId = '1'; 

    public function apiToggleLike() {
        $postId = $_POST['post_id'] ?? null;
        if($postId) {
            echo json_encode($this->postRepository->toggleLike($postId, $this->fakeUserId));
        }
    }

    public function apiToggleSave() {
        $postId = $_POST['post_id'] ?? null;
        if($postId) {
            echo json_encode($this->postRepository->toggleBookmark($postId, $this->fakeUserId));
        }
    }

    public function apiAddComment() {
        $postId = $_POST['post_id'] ?? null;
        $content = $_POST['content'] ?? null;
        
        if($postId && $content) {
            if($this->postRepository->addComment($postId, $this->fakeUserId, $content)) {
                echo json_encode(['status' => 'success', 'message' => 'Đã gửi bình luận']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi gửi bình luận']);
            }
        }
    }
}