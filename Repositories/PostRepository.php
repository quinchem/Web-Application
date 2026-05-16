<?php

require_once __DIR__ . '/../Configs/Database.php';
require_once __DIR__ . '/../App/Models/Post.php';

class PostRepository
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getUserPostsForAdmin($filters = []) 
{
    $sql = "
        SELECT 
            p.post_id,
            p.title,
            p.status,
            p.view_count,
            p.created_at,
            p.is_trending,
            u.user_id,
            u.full_name AS author_name,
            c.name AS category_name,
            parent.name AS parent_category_name
        FROM Post p
        JOIN `User` u ON p.user_id = u.user_id
        JOIN Role r ON u.role_id = r.role_id
        JOIN Category c ON p.category_id = c.category_id
        LEFT JOIN Category parent ON c.parent_id = parent.category_id
        WHERE r.role_name = 'client'
    ";

    $params = [];

    if (!empty($filters['status'])) {
        $sql .= " AND p.status = :status";
        $params['status'] = $filters['status'];
    } else {
        $sql .= " AND p.status != 'hidden'";
    }

    if (!empty($filters['keyword'])) {
        $sql .= " AND (p.title LIKE :keyword OR u.full_name LIKE :keyword)";
        $params['keyword'] = '%' . $filters['keyword'] . '%';
    }

    if (!empty($filters['category_id'])) {
        $sql .= " AND (c.category_id = :category_id OR c.parent_id = :category_id)";
        $params['category_id'] = $filters['category_id'];
    }

    if (!empty($filters['author_id'])) {
        $sql .= " AND u.user_id = :author_id";
        $params['author_id'] = $filters['author_id'];
    }

    if (!empty($filters['date'])) {
        $sql .= " AND DATE(p.created_at) = :date";
        $params['date'] = $filters['date'];
    }

    $sql .= " ORDER BY p.created_at DESC LIMIT 10";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    $posts = [];

    foreach ($stmt->fetchAll() as $row) {
        $posts[] = new Post($row);
    }

    return $posts;
}

public function getCategoriesForFilter()
{
    $sql = "SELECT category_id, name FROM Category ORDER BY name ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAuthorsForFilter()
{
    $sql = "
        SELECT DISTINCT u.user_id, u.full_name
        FROM `User` u
        JOIN Role r ON u.role_id = r.role_id
        JOIN Post p ON p.user_id = u.user_id
        WHERE r.role_name = 'client'
        ORDER BY u.full_name ASC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function hidePost($postId)
{
    $sql = "
        UPDATE Post
        SET status = 'hidden'
        WHERE post_id = :post_id
    ";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        'post_id' => $postId
    ]);
}
    public function countUserPosts()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'client'
        ";

        return $this->conn->query($sql)->fetch()['total'];
    }

    public function countUserPostsByStatus($status)
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'client'
            AND p.status = :status
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['status' => $status]);

        return $stmt->fetch()['total'];
    }

    public function countTrendingUserPosts()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'client'
            AND p.is_trending = TRUE
        ";

        return $this->conn->query($sql)->fetch()['total'];
    }

    /**
     * Lấy bài viết tiêu điểm/nổi bật (hero post)
     * 
     * Lấy bài viết trending (xu hướng) mới nhất để hiển thị ở hero banner
     * 
     * @return array Mảng dữ liệu bài viết hoặc false nếu không có
     */
    public function getHeroPost() {
        $sql = "SELECT p.*, p.thumbnail_URL AS thumbnail_url, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.status = 'approved' AND p.is_trending = 1 
                ORDER BY p.view_count DESC LIMIT 1";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách bài viết theo tên danh mục
     * 
     * @param string $parentCategoryName Tên danh mục cha (ví dụ: "Thời sự", "Kinh tế")
     * @param int $limit Số lượng bài viết cần lấy (mặc định: 4)
     * @return array Mảng bài viết đã được phê duyệt
     */
    public function getPostsByParentCategory($parentCategoryName, $limit = 10)
{
    // Logic: 
    // 1. Tìm ID của danh mục cha dựa trên tên (Thời sự/Kinh tế).
    // 2. Tìm tất cả ID của danh mục con có parent_id bằng ID cha đó.
    // 3. Lấy bài viết thuộc danh mục cha HOẶC nằm trong danh sách ID con.

    $sql = "SELECT p.*, p.thumbnail_URL AS thumbnail_url, p.summary, c.name as category_name, u.full_name as author_name
            FROM Post p 
            JOIN Category c ON p.category_id = c.category_id 
            JOIN User u ON p.user_id = u.user_id
            WHERE p.status = 'approved' 
            AND (
                c.name = :parentName 
                OR c.parent_id = (SELECT category_id FROM Category WHERE name = :parentName LIMIT 1)
            )
            ORDER BY p.view_count DESC 
            LIMIT :limit";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':parentName', $parentCategoryName);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
  



    /**
     * Lấy danh sách bài viết trending (xu hướng)
     * 
     * @param int $limit Số lượng bài viết cần lấy (mặc định: 6)
     * @return array Mảng bài viết trending
     */
    public function getTrendingPosts($limit = 6) {
        $sql = "SELECT p.*, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.status = 'approved' AND p.is_trending = 1 
                ORDER BY p.view_count DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả danh mục chính (không có danh mục cha)
     * 
     * @return array Mảng danh mục
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM Category WHERE parent_id IS NULL ORDER BY name";
        return $this->conn->query($sql)->fetchAll();
    }

    /**
     * Lấy chi tiết bài viết theo ID
     * 
     * @param int $postId ID của bài viết
     * @return array Mảng dữ liệu bài viết hoặc false nếu không tìm thấy
     */
    public function getPostById($postId) {
        $sql = "SELECT p.*, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.post_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $postId]);
        return $stmt->fetch();
    }

}