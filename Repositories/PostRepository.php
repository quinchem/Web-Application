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

    public function getUserPostsForAdmin()
    {
        $sql = "
            SELECT 
                p.post_id,
                p.title,
                p.status,
                p.view_count,
                p.published_at,
                p.created_at,
                p.is_trending,
                u.full_name AS author_name,
                c.name AS category_name,
                parent.name AS parent_category_name
            FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            JOIN Category c ON p.category_id = c.category_id
            LEFT JOIN Category parent ON c.parent_id = parent.category_id
            WHERE r.role_name = 'client'
            ORDER BY p.created_at DESC
            LIMIT 10
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $posts = [];

        foreach ($stmt->fetchAll() as $row) {
            $posts[] = new Post($row);
        }

        return $posts;
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

    // Lấy bài viết tiêu điểm 
    public function getHeroPost() {
        $sql = "SELECT p.*, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.status = 'approved' AND p.is_trending = 1 
                ORDER BY p.published_at DESC LIMIT 1";
        return $this->conn->query($sql)->fetch();
    }

    // Lấy danh sách bài viết theo tên danh mục
    public function getPostsByCategory($categoryName, $limit = 4) {
        $sql = "SELECT p.*, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE c.name = :catName AND p.status = 'approved' 
                ORDER BY p.published_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':catName', $categoryName);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}