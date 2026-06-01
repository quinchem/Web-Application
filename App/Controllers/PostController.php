<?php

namespace App\Controllers;

/**
 * PostController.php
 * Controller xử lý các yêu cầu liên quan đến bài viết (post)
 * Quản lý việc lấy dữ liệu từ database và truyền đến view
 */

require_once __DIR__ . '/../../Repositories/PostRepository.php';
require_once __DIR__ . '/../../Repositories/CategoryRepository.php';
require_once __DIR__ . '/CategoryController.php';


/**
 * Lớp PostController
 * Xử lý logic chính cho trang web phía client liên quan đến bài viết
 */
class PostController
{
    private $postRepository;
    private $categoryController;        

    // Hàm khởi tạo, tạo instance của PostRepository để sử dụng trong các phương thức khác
    public function __construct()
    {
        $this->postRepository = new \PostRepository();
        $this->categoryController = new CategoryController();
    }

    // Hàm hiển thị trang chủ với các bài viết nổi bật và theo danh mục
    public function homepage()
    {
        // Lấy bài viết bao gồm cả các danh mục con
        $thoiSu = $this->postRepository->getPostsByParentCategory('Thời sự', 4);
        $kinhTe = $this->postRepository->getPostsByParentCategory('Kinh tế', 4);
        $bannerPosts = $this->postRepository->getTrendingGlobal(5);

        require __DIR__ . '/../Views/Client/Home.php';
    }

    // Hàm hiển thị kết quả tìm kiếm với các bộ lọc nâng cao
    public function searchResult()
    {
        $keyword = trim($_GET['key'] ?? '');

        $time = $_GET['time'] ?? 'newest';
        $fromDate = $_GET['from_date'] ?? '';
        $toDate = $_GET['to_date'] ?? '';

        $categoryIds = $_GET['categories'] ?? [];

        if (!is_array($categoryIds)) {
            $categoryIds = [$categoryIds];
        }

        $categoryIds = array_values(array_filter($categoryIds, function ($item) {
            return $item !== '';
        }));

        $author = trim($_GET['author'] ?? '');

        // Trang phân trang, không dùng $_GET['page']
        // vì page=search_result là route
        $paginationPage = max(1, (int)($_GET['p'] ?? 1));

        $limit = 3;
        $offset = ($paginationPage - 1) * $limit;

        $filters = [
            'keyword' => $keyword,
            'time' => $time,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'categories' => $categoryIds,
            'author' => $author,
            'limit' => $limit,
            'offset' => $offset
        ];

        $posts = $this->postRepository->searchPosts($filters);
        $totalPosts = $this->postRepository->countSearchPosts($filters);

        $totalPages = (int)ceil($totalPosts / $limit);

        if ($paginationPage > $totalPages && $totalPages > 0) {
            $paginationPage = $totalPages;
            $filters['offset'] = ($paginationPage - 1) * $limit;
            $posts = $this->postRepository->searchPosts($filters);
        }
        // Biến này truyền qua Result.php
        $currentPaginationPage = $paginationPage;
        $categories = $this->categoryController->getCategories();

        require __DIR__ . '/../Views/Client/Search/Result.php';
    }

    // Hàm hiển thị trang danh mục với danh sách bài viết theo danh mục
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

    // Hàm xử lý ẩn bài viết
    public function hidePost()
    {
        if (!isset($_GET['id'])) {
            $from = $_GET['from'] ?? 'admin_user_posts';
            header('Location: Admin_index.php?page=' . $from);
            exit;
        }

        $postId = $_GET['id'];
        $from = $_GET['from'] ?? 'admin_user_posts';

        $this->postRepository->hidePost($postId);

        header('Location: Admin_index.php?page=' . $from);
        exit;
    }

    // Hàm xử lý ẩn bài viết
    public function unhidePost()
    {
        if (!isset($_GET['id'])) {
            $from = $_GET['from'] ?? 'admin_user_posts';
            header('Location: Admin_index.php?page=' . $from);
            exit;
        }

        $postId = $_GET['id'];
        $from = $_GET['from'] ?? 'admin_user_posts';

        $this->postRepository->unhidePost($postId);

        header('Location: Admin_index.php?page=' . $from);
        exit;
    }

    // Hàm hiển thị trang chi tiết bài viết
    public function reviewPostPage()
{
    $id = $_GET['id'] ?? '';
    if (!$id) {
        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    $post = $this->postRepository->getPostById($id);
    $tags = $this->postRepository->getPostTags($id);

    if (!$post) {
        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    require_once __DIR__ . '/../Views/Admin/Post/ReviewPost.php';
}

    // Hàm xử lý duyệt bài viết
    public function reviewPost()
    {
        if (!isset($_GET['id'])) {
            header('Location: Admin_index.php?page=admin_user_posts');
            exit;
        }

        $postId = $_GET['id'];
        $decision = $_GET['decision'] ?? 'approved';
        $reason = urldecode($_GET['reason'] ?? '');

        // Chỉ cho phép 2 giá trị hợp lệ
        if (!in_array($decision, ['approved', 'rejected'])) {
            header('Location: Admin_index.php?page=admin_user_posts');
            exit;
        }

        $this->postRepository->reviewPost($postId, $decision, $reason);

        header('Location: Admin_index.php?page=admin_user_posts');
        exit;
    }

    // Hàm kiểm tra người dùng đã đăng nhập chưa và lấy ID người dùng hiện tại
    private function getCurrentUserId()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        return $_SESSION['user_id'] ?? null;
    }

    // Hàm xử lý like bài viết qua API
    public function api_like()
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            echo json_encode(['status' => 'unauthorized']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        if ($postId) {
            $result = $this->postRepository->toggleLike($postId, $userId);
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }


    // Hàm xử lý lưu bài viết qua API
    public function api_save()
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            echo json_encode(['status' => 'unauthorized']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        if ($postId) {
            // Hàm toggleBookmark của bạn phải trả về mảng có key 'action' => 'saved' hoặc 'unsaved'
            echo json_encode($this->postRepository->toggleBookmark($postId, $userId));
        }
    }


    // Hàm xử lý thêm bình luận qua API
    public function apiAddComment()
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            echo json_encode(['status' => 'unauthorized', 'message' => 'Vui lòng đăng nhập.']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = $_POST['content'] ?? null;
        $parentId = $_POST['parent_id'] ?? null;

        if ($postId && $content) {
            if ($this->postRepository->addComment($postId, $userId, $content)) {
                // Trả về dữ liệu để AJAX tự động vẽ lên màn hình mà không cần reload
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đã gửi bình luận',
                    'comment' => [
                        'full_name' => $_SESSION['full_name'] ?? $_SESSION['user_name'],
                        'avatar' => $_SESSION['avatar'] ?? $_SESSION['avatar_url'] ?? 'default-avatar.png',
                        'content' => nl2br(htmlspecialchars($content)),
                        'created_at' => date('d/m/Y H:i') // Lấy giờ hiện tại
                    ]
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi gửi bình luận']);
            }
        }
    }

     // Hàm xử lý hiển thị bình luận của bài viết qua API (dùng cho phần bình luận ở trang chi tiết bài viết)
    public function apiGetComments()
    {
        $postId = $_GET['post_id'] ?? null;
        $page = isset($_GET['cpage']) ? max(1, (int) $_GET['cpage']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        if (!$postId) {
            echo json_encode(['html' => '', 'pagination' => '']);
            return;
        }

        $comments = $this->postRepository->getCommentsByPostId($postId, $limit, $offset);
        $totalComments = $this->postRepository->countTotalCommentsByPostId($postId);
        $totalPages = ceil($totalComments / $limit);

        $defaultAvatar = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

        // Build HTML comment
        ob_start();
        if (empty($comments)): ?>
            <p class="text-muted text-center py-4 bg-light rounded">Chưa có bình luận nào.</p>
            <?php else:
            foreach ($comments as $cmt): ?>
                <div class="d-flex mb-4 pb-4 border-bottom">
                    <img src="<?= $defaultAvatar ?>" class="comment-avatar me-3" alt="Avatar">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-bold" style="color: var(--navy);">
                                <?= htmlspecialchars($cmt['full_name']) ?>
                            </div>
                            <div class="small text-muted">
                                <?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?>
                            </div>
                        </div>
                        <div class="text-dark" style="font-size:0.95rem; line-height:1.6;">
                            <?= nl2br(htmlspecialchars($cmt['content'])) ?>
                        </div>
                        <a href="#" class="text-danger fw-bold text-decoration-none mt-2 d-inline-block"
                            style="font-size:0.8rem; color:var(--red) !important;">TRẢ LỜI</a>
                    </div>
                </div>
            <?php endforeach;
        endif;
        $html = ob_get_clean();

        // Build HTML pagination
        ob_start();
        if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center align-items-center mt-4 gap-3">
                <?php if ($page > 1): ?>
                    <button onclick="loadComments(<?= $page - 1 ?>)" class="btn btn-pagination-arrow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                <?php endif; ?>

                <span class="comment-pagination-text">Trang <?= $page ?> / <?= $totalPages ?></span>

                <?php if ($page < $totalPages): ?>
                    <button onclick="loadComments(<?= $page + 1 ?>)" class="btn btn-pagination-arrow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                <?php endif; ?>
            </div>
<?php endif;
        $pagination = ob_get_clean();

        header('Content-Type: application/json');
        echo json_encode([
            'html' => $html,
            'pagination' => $pagination,
            'total' => $totalComments
        ]);
    }


    // Hàm xử lý hiển thị chi tiết bài viết
    public function post()
    {
        $postId = $_GET['id'] ?? null;
        if (!$postId) {
            $this->homepage();
            return;
        }

        $this->postRepository->incrementViewCount($postId);
        $post = $this->postRepository->getPostById($postId);

        if (!$post) {
            echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2>Bài viết không tồn tại hoặc đã bị ẩn!</h2>
                <a href='Index.php'>Về trang chủ</a>
              </div>";
            exit;
        }

        $userId = $this->getCurrentUserId();
        $tags = $this->postRepository->getPostTags($postId);
        $comments = [];
        $totalComments = 0;
        $totalPages = 1;
        $isSaved = false;
        $isLiked = false;
        if ($userId) {
            $isLiked = $this->postRepository->isLiked($postId, $userId);
            $isSaved = $this->postRepository->isBookmarked($postId, $userId);
        }
        $recommendedPosts = $this->postRepository->getRecommendedByCategory(
            $postId,
            $post['category_id'],
            8
        );
        $trendingGlobal = $this->postRepository->getTrendingGlobal(5);
        $page = isset($_GET['cpage']) ? max(1, (int) $_GET['cpage']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $totalComments = $this->postRepository->countTotalCommentsByPostId($postId);
        if ($totalComments > 0) {
            $comments = $this->postRepository->getCommentsByPostId($postId, $limit, $offset);
            $totalPages = ceil($totalComments / $limit);
        }

        require __DIR__ . '/../Views/Client/Post/Detail.php';
    }
    // Hàm xử lý hiển thị các bài viết đã lưu
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
        $savedCurrentPage = isset($_GET['saved_page']) ? (int) $_GET['saved_page'] : 1;

        if ($savedCurrentPage < 1) {
            $savedCurrentPage = 1;
        }

        $savedOffset = ($savedCurrentPage - 1) * $savedLimit;

        $savedTotalPosts = $this->postRepository->countSavedPostsByUser($userId);
        $savedTotalPages = (int) ceil($savedTotalPosts / $savedLimit);

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

    // Hàm xử lý hiển thị trang quản lý bài viết cá nhân của tôi
    public function myPostsPage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo "<div class='alert alert-warning m-0'>Vui lòng đăng nhập để xem bài viết của bạn.</div>";
            exit;
        }

        $myPostLimit = 4;

        $myPostCurrentPage = isset($_GET['my_post_page'])
            ? (int)$_GET['my_post_page']
            : 1;

        if ($myPostCurrentPage < 1) {
            $myPostCurrentPage = 1;
        }

        $myPostKeyword = trim($_GET['keyword'] ?? '');
        $myPostCategory = trim($_GET['category'] ?? '');
        $myPostStatus = trim($_GET['status'] ?? '');
        $myPostDate = trim($_GET['date'] ?? '');

        $myPostOffset = ($myPostCurrentPage - 1) * $myPostLimit;

        $myPostTotalPosts = $this->postRepository->countMyPostsByUser(
            $userId,
            $myPostKeyword,
            $myPostCategory,
            $myPostStatus,
            $myPostDate
        );

        $myPostTotalPages = (int)ceil($myPostTotalPosts / $myPostLimit);

        if ($myPostTotalPages > 0 && $myPostCurrentPage > $myPostTotalPages) {
            $myPostCurrentPage = $myPostTotalPages;
            $myPostOffset = ($myPostCurrentPage - 1) * $myPostLimit;
        }

        $myPosts = $this->postRepository->getMyPostsByUser(
            $userId,
            $myPostLimit,
            $myPostOffset,
            $myPostKeyword,
            $myPostCategory,
            $myPostStatus,
            $myPostDate
        );

        $myPostTotalAll = $this->postRepository->countMyPostsByUser($userId);
        $myPostTotalApproved = $this->postRepository->countMyPostsByUserAndStatus($userId, 'approved');
        $myPostTotalPending = $this->postRepository->countMyPostsByUserAndStatus($userId, 'pending');
        $myPostTotalDraft = $this->postRepository->countMyPostsByUserAndStatus($userId, 'draft');

        $myPostCategories = $this->categoryController->getCategories();

        include __DIR__ . '/../Views/Client/Post/my_posts.php';
        exit;
    }

    // Hàm xử lý hiển thị trang tạo bài viết mới
    public function clientCreatePostPage(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $categories = $this->postRepository->getCategoriesForFilter();

        require_once __DIR__ . '/../Views/Client/Post/Create.php';
    }

    // Hàm xử lý lưu bài viết mới hoặc cập nhật nháp qua API
    public function clientStorePost(): void
    {
        // Luôn trả về JSON
        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.']);
            exit;
        }

        $postId  = trim($_POST['post_id'] ?? '');   // có giá trị khi đang cập nhật nháp
        $title   = trim($_POST['title']   ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = $_POST['content']      ?? '';
        $action  = $_POST['action']       ?? 'draft';

        if ($title === '' || $content === '') {
            echo json_encode(['success' => false, 'message' => 'Tiêu đề và nội dung không được để trống.']);
            exit;
        }

        // Upload thumbnail (nếu có)
        $thumbnailUrl = null;
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $thumbnailUrl = $this->uploadToCloudinary($_FILES['thumbnail']);
        }

        // Tags
        $tags = [];
        if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
            $tags = array_unique(array_filter(array_map('trim', $_POST['tags'])));
            $tags = array_slice($tags, 0, 10);
        }

        // Category — ưu tiên danh mục con
        $categoryId = !empty($_POST['category_id'])
            ? $_POST['category_id']
            : ($_POST['parent_category'] ?? null);

        // Ngày xuất bản
        $publishAt = null;
        if (!empty($_POST['publish_at'])) {
            $parsed = strtotime($_POST['publish_at']);
            if ($parsed !== false) {
                $publishAt = date('Y-m-d H:i:s', $parsed);
            }
        }

        $status = ($action === 'publish') ? 'pending' : 'draft';

// Cập nhật bài viết
if ($postId !== '') {
    $data = [
        'title'        => $title,
        'summary'      => $summary,
        'content'      => $content,
        'category_id'  => $categoryId,
        'status'       => $status,
        'published_at' => $publishAt,
    ];

    if ($thumbnailUrl) {
        $data['thumbnail_URL'] = $thumbnailUrl;
    } else {
        $existingThumb = trim($_POST['existing_thumbnail'] ?? '');
        if ($existingThumb === '') {
            $data['thumbnail_URL'] = null;
        }
    }

    $this->postRepository->updatePost($postId, $data);

    if (!empty($tags)) {
        $this->postRepository->syncTags($postId, $tags);
    }

    echo json_encode(['success' => true, 'post_id' => $postId]);
    exit;
}

// Tạo bài viết mới
$newPostId = $this->postRepository->clientCreatePost([
    'user_id'       => $_SESSION['user_id'],
    'title'         => $title,
    'summary'       => $summary,
    'content'       => $content,
    'thumbnail_URL' => $thumbnailUrl,
    'category_id'   => $categoryId,
    'status'        => $status,
    'publish_at'    => $publishAt,
]);

if ($newPostId && !empty($tags)) {
    $this->postRepository->syncTags($newPostId, $tags);
}

if ($newPostId) {
    echo json_encode(['success' => true, 'post_id' => $newPostId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Đã có lỗi xảy ra, vui lòng thử lại.']);
}
exit;
    }

    // Hàm xử lý xóa bài viết
    public function clientDeletePost(): void
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
            exit;
        }

        $postId = trim($_POST['post_id'] ?? '');

        if (!$postId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu post_id.']);
            exit;
        }

        // Kiểm tra bài thuộc về user hiện tại và đang ở trạng thái draft
        $post = $this->postRepository->getPostById($postId);

        if (
            !$post
            || $post['user_id'] !== $_SESSION['user_id']   // không phải của user này
            // Chỉ cho xoá nháp — bỏ dòng dưới nếu muốn cho xoá mọi trạng thái
        ) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền xoá bài này.']);
            exit;
        }

        $result = $this->postRepository->deletePost($postId);
        echo json_encode(['success' => (bool)$result]);
        exit;
    }

     // Hàm xử lý hiển thị trang chỉnh sửa bài viết
    public function clientEditPostPage(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $postId = $_GET['id'] ?? null;

        if (!$postId) {
            header('Location: index.php?page=client_profile&tab=my_posts');
            exit;
        }

        $post = $this->postRepository->getPostById($postId);

        // Kiểm tra bài thuộc về user này
        if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=client_profile&tab=my_posts');
            exit;
        }

        $categories = $this->postRepository->getCategoriesForFilter();
        $tags       = $this->postRepository->getPostTags($postId);

        require_once __DIR__ . '/../Views/Client/Post/Edit.php';
    }

    // Hàm xử lý hiển thị trang quản lý bài viết của người dùng cho Admin
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

        $posts         = $this->postRepository->getUserPostsForAdmin($filters, $perPage, $offset);
        $categories    = $this->postRepository->getCategoriesForFilter(); // ← THÊM
        $authors       = $this->postRepository->getAuthorsForFilter();

        $totalPosts    = $this->postRepository->countUserPosts();
        $totalForPages = $this->postRepository->countUserPostsFiltered($filters);
        $pendingPosts  = $this->postRepository->countUserPostsByStatus('pending');
        $hiddenPosts   = $this->postRepository->countUserPostsByStatus('hidden');
        $trendingPosts = $this->postRepository->countTrendingUserPosts();

        $totalPages = (int)ceil($totalForPages / $perPage);

        require_once __DIR__ . '/../Views/Admin/Post/Index.php';
    }

   
    // Hàm xử lý hiển thị trang quản lý tất cả bài viết cho Admin
    public function adminPosts()
    {
        $this->postRepository->autoPublishDuePosts();

        $filters = [
            'keyword'     => $_GET['keyword']     ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'author_id'   => $_GET['author_id']   ?? '', 
            'status'      => $_GET['status']       ?? '',
            'date'        => $_GET['date']         ?? '',
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
        $categories    = $this->postRepository->getCategoriesForFilter();
        $adminAuthors  = $this->postRepository->getAdminAuthors();  

        require_once __DIR__ . '/../Views/Admin/Post/IndexAdmin.php';
    }
    public function createPost()
    {
        $categories = $this->postRepository->getCategoriesForFilter();
        require_once __DIR__ . '/../Views/Admin/Post/Create.php';
    }

    // Hàm xử lý lưu bài viết mới
    public function storePost()
    {
        $title      = trim($_POST['title']    ?? '');
        $summary    = trim($_POST['summary']  ?? '');
        $content    = $_POST['content']       ?? '';
        $categoryId = $_POST['category_id']   ?? '';
        $tags       = $_POST['tags']          ?? [];
        $publishAt  = $_POST['publish_at']    ?? null;
        $action     = $_POST['action']        ?? 'draft';

        $authorId = $_SESSION['user']->user_id ?? null;

        // Thay move_uploaded_file → upload Cloudinary
        $thumbnailUrl = null;
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $thumbnailUrl = $this->uploadToCloudinary($_FILES['thumbnail']);
        }

        $status = ($action === 'publish') ? 'approved' : 'draft';

        $postId = $this->postRepository->createPost([
            'title'         => $title,
            'summary'       => $summary,
            'content'       => $content,
            'category_id'   => $categoryId,
            'author_id'     => $authorId,
            'thumbnail_url' => $thumbnailUrl,
            'status'        => $status,
            'publish_at'    => $publishAt ?: null,
            'tags'          => $tags,
        ]);

        header('Location: Admin_index.php?page=admin_posts');
        exit;
    }

    //Chỉnh sửa bài viết admin
    public function editPost()
    {
        $id = $_GET['id'] ?? ''; 

        if (!$id) {
            header('Location: Admin_index.php?page=admin_posts');
            exit;
        }

        $post       = $this->postRepository->getPostById($id);
        $categories = $this->postRepository->getCategoriesForFilter();
        $tags       = $this->postRepository->getPostTags($id);

        if (!$post) {
            header('Location: Admin_index.php?page=admin_posts');
            exit;
        }

        $allParents  = array_filter($categories, fn($c) => empty($c['parent_id']));
        $allChildren = array_filter($categories, fn($c) => !empty($c['parent_id']));

        require_once __DIR__ . '/../Views/Admin/Post/EditPost.php';
    }

    // Hàm xử lý cập nhật bài viết
    public function updatePost()
    {
        $id = $_POST['post_id'] ?? '';

        if (!$id) {
            header('Location: Admin_index.php?page=admin_posts');
            exit;
        }

        $title     = trim($_POST['title']       ?? '');
        $summary   = trim($_POST['summary']     ?? '');
        $content   = $_POST['content']          ?? '';
        $catId     = $_POST['category_id']      ?? '';
        $subCatId  = $_POST['sub_category_id']  ?? '';
        $publishAt = $_POST['published_at']     ?? null;
        $tags      = $_POST['tags']             ?? [];

        $finalCatId = !empty($subCatId) ? $subCatId : $catId;

        $currentPost   = $this->postRepository->getPostById($id);
        $currentStatus = $currentPost['status'] ?? 'draft';

        $data = [
            'title'        => $title,
            'summary'      => $summary,
            'content'      => $content,
            'category_id'  => $finalCatId,
            'published_at' => $publishAt ?: null,
            'status'       => $currentStatus,
        ];

        // Upload Cloudinary nếu có ảnh mới
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $url = $this->uploadToCloudinary($_FILES['thumbnail']);
            if ($url) {
                $data['thumbnail_URL'] = $url;
            }
        }

        $this->postRepository->updatePost($id, $data);
        $this->postRepository->syncTags($id, $tags);

        header('Location: Admin_index.php?page=edit_post&id=' . $id . '&success=1');
        exit;
    }

    // Hàm xử lý upload ảnh lên Cloudinary và trả về URL
    private function uploadToCloudinary($file): string
    {
        $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'];
        $apiKey    = $_ENV['CLOUDINARY_API_KEY'];
        $apiSecret = $_ENV['CLOUDINARY_API_SECRET'];

        $folder    = 'tramtinviet/posts';
        $timestamp = time();
        $signature = sha1("folder={$folder}&timestamp={$timestamp}{$apiSecret}");

        $cfile = new \CURLFile($file['tmp_name'], $file['type'], $file['name']);

        $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file'      => $cfile,
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder'    => $folder,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result['secure_url'] ?? '';
    }

    // Hàm xử lý xóa bài viết
    public function deletePost()
    {
        $id = $_POST['post_id'] ?? '';

        if (!$id) {
            echo json_encode(['success' => false]);
            exit;
        }

        $result = $this->postRepository->deletePost($id);

        echo json_encode(['success' => (bool)$result]);
        exit;
    }
    public function categoryDetail()
    {
        $categorySlug = $_GET['slug'] ?? null;
        $categoryName = $_GET['name'] ?? null;
        $categoryDesc = null;

        // Mô tả tĩnh theo slug (chưa có trong database)
        $categoryDescriptions = [
            'thoi-su'  => 'Cập nhật những diễn biến quan trọng nhất về chính trị, xã hội và an ninh quốc phòng trong và ngoài nước qua lăng kính phân tích sâu sắc.',
            'kinh-te'  => 'Thông tin kinh tế vĩ mô, thị trường tài chính và các xu hướng phát triển kinh tế trong nước và quốc tế.',
        ];

        if ($categorySlug) {
            $cat = $this->postRepository->getCategoryBySlug($categorySlug);
            $categoryName = $cat['name'] ?? $categoryName;
            $categoryDesc = $categoryDescriptions[$categorySlug] ?? null;
        }

        if ($categoryName && !$categorySlug) {
            $cat = $this->postRepository->getCategoryByName($categoryName);
            $categorySlug = $cat['slug'] ?? '';
            $categoryDesc = $categoryDescriptions[$categorySlug] ?? null;
        }

        if (!$categoryName) {
            $this->homepage();
            return;
        }

        $posts = $this->postRepository->getPostsByParentCategoryGrouped($categoryName, 4);

        require __DIR__ . '/../Views/Client/Category/Detail.php';
    }

    // Hàm xử lý hiển thị trang danh mục con
    public function subCategoryDetail()
    {
        $slug = $_GET['slug'] ?? null;

        if (!$slug) {
            $this->homepage();
            return;
        }
        // BÀI NỔI BẬT (NHIỀU VIEW NHẤT)
        $featuredPost = $this->postRepository
            ->getFeaturedPostByCategory($slug);

        $featuredId = $featuredPost['post_id'] ?? 0;

        // PHÂN TRANG
        $pageNumber = isset($_GET['p'])
            ? (int) $_GET['p']
            : 1;

        if ($pageNumber < 1) {
            $pageNumber = 1;
        }

        $limit = 10;

        $offset = ($pageNumber - 1) * $limit;
        $posts = $this->postRepository
            ->getPostsByCategorySlug(
                $slug,
                $featuredId,
                $limit,
                $offset
            );

        $totalPosts = $this->postRepository
            ->countPostsByCategorySlug($slug);

        $totalPages = ceil($totalPosts / $limit);
        $categoryName =
            $featuredPost['category_name']
            ?? ($posts[0]['category_name'] ?? '');

        require __DIR__ . '/../Views/Client/Category/Detail2.php';
    }

    // Hàm xử lý upload ảnh nội dung bài viết qua API (dùng cho trình soạn thảo CKEditor)
    public function apiUploadImage(): void {
    header('Content-Type: application/json; charset=utf-8');

    // Kiểm tra đăng nhập
    if (empty($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
        exit;
    }

    // Kiểm tra file
    if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $code = $_FILES['image']['error'] ?? -1;
        echo json_encode(['success' => false, 'message' => 'Lỗi file upload. Mã: ' . $code]);
        exit;
    }

    // Kiểm tra config Cloudinary
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
    $apiKey    = $_ENV['CLOUDINARY_API_KEY']    ?? '';
    $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

    if (!$cloudName || !$apiKey || !$apiSecret) {
        echo json_encode(['success' => false, 'message' => 'Thiếu cấu hình Cloudinary.']);
        exit;
    }

    $fileTmp  = $_FILES['image']['tmp_name'];
    $fileType = $_FILES['image']['type'];
    $fileName = $_FILES['image']['name'];

    // Chỉ chấp nhận ảnh
    if (!str_starts_with($fileType, 'image/')) {
        echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh.']);
        exit;
    }

    $folder    = 'tramtinviet/content';   // khác folder avatar để dễ quản lý
    $timestamp = time();
    $signature = sha1("folder={$folder}&timestamp={$timestamp}{$apiSecret}");

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'file'      => new \CURLFile($fileTmp, $fileType, $fileName),
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder'    => $folder,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        echo json_encode(['success' => false, 'message' => 'Không kết nối được Cloudinary: ' . $curlError]);
        exit;
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200 || empty($result['secure_url'])) {
        echo json_encode([
            'success' => false,
            'message' => $result['error']['message'] ?? 'Cloudinary từ chối upload.',
        ]);
        exit;
    }

    echo json_encode(['success' => true, 'url' => $result['secure_url']]);
    exit;
}
}