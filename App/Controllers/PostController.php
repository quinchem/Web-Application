<?php
/**
 * PostController.php
 * 
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
     * 
     * Lấy:
     * - 1 bài viết nổi bật (hero post)
     * - 5 bài viết từ danh mục "Thời sự"
     * - 4 bài viết từ danh mục "Kinh tế"
     * - 4 bài viết từ danh mục "Chính trị"
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
     * 
     * @param int $categoryId ID của danh mục
     */
    public function category($categoryId)
    {
        // Lấy danh sách bài viết theo ID danh mục
        $posts = $this->postRepository->getPostsByParentCategory($categoryId);
        require __DIR__ . '/../Views/Client/Category/Detail.php';
    }

    /**
     * Hiển thị chi tiết một bài viết cụ thể
     * 
     * @param int $postId ID của bài viết
     */
    public function post($postId)
    {
        // Lấy thông tin chi tiết bài viết
        $post = $this->postRepository->getPostById($postId);
        
        // Lấy danh sách bình luận của bài viết
        $comments = $this->postRepository->getCommentsByPostId($postId);
        
        require __DIR__ . '/../Views/Client/Post/Detail.php';
    }

    /**
     * Hiển thị danh sách bài viết cho admin
     * 
     * Lấy số liệu thống kê: tổng, pending, hidden, trending
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
}