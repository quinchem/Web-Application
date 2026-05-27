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

    public function getUserPostsForAdmin($filters = [], $limit = 10, $offset = 0)
    {
        $sql = "
        SELECT 
            p.post_id, p.title, p.status, p.view_count, p.created_at, p.is_trending,
            u.user_id, u.full_name AS author_name,
            c.name AS category_name, parent.name AS parent_category_name
        FROM Post p
        JOIN `User` u ON p.user_id = u.user_id
        JOIN Role r ON u.role_id = r.role_id
        JOIN Category c ON p.category_id = c.category_id
        LEFT JOIN Category parent ON c.parent_id = parent.category_id
        WHERE r.role_name = 'client'
    ";

        $params = [];

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

        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

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
        $sql = "UPDATE Post SET status = 'hidden' WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['post_id' => $postId]);
    }

    public function countUserPosts()
    {
        $sql = "
        SELECT COUNT(*) AS total FROM Post p
        JOIN `User` u ON p.user_id = u.user_id
        JOIN Role r ON u.role_id = r.role_id
        WHERE r.role_name = 'client'
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function countUserPostsFiltered($filters = [])
    {
        $sql = "
        SELECT COUNT(*) AS total FROM Post p
        JOIN `User` u ON p.user_id = u.user_id
        JOIN Role r ON u.role_id = r.role_id
        WHERE r.role_name = 'client'
    ";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $filters['status'];
        } else {
            $sql .= " AND p.status != 'hidden'";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.title LIKE :keyword OR u.full_name LIKE :keyword)";
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND (p.category_id = :category_id)";
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

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }
    public function countUserPostsByStatus($status)
    {
        $sql = "
            SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'client' AND p.status = :status
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetch()['total'];
    }

    public function countTrendingUserPosts()
    {
        $sql = "
            SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'client' AND p.is_trending = TRUE
        ";
        return $this->conn->query($sql)->fetch()['total'];
    }

    public function reviewPost(string $postId, string $decision, string $reason = ''): bool
    {
        $sql = "UPDATE Post          
            SET status      = :status,
                review_note = :reason,
                reviewed_at = NOW()
            WHERE post_id   = :post_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $decision,
            ':reason' => $reason,
            ':post_id' => $postId
        ]);
    }

    public function getHeroPost()
    {
        $sql = "SELECT p.*, p.thumbnail_URL AS thumbnail_url, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.status = 'approved' AND p.is_trending = 1 
                ORDER BY p.view_count DESC LIMIT 1";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function getPostsByParentCategory($parentCategoryName, $limit = 10)
    {
        $sql = "SELECT p.*, p.thumbnail_URL AS thumbnail_url, p.summary, c.name as category_name, u.full_name as author_name
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id
                WHERE p.status = 'approved' 
                AND (c.name = :parentName OR c.parent_id = (SELECT category_id FROM Category WHERE name = :parentName LIMIT 1))
                ORDER BY p.view_count DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':parentName', $parentCategoryName);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTrendingPosts($limit = 6)
    {
        $sql = "SELECT p.*, c.name as category_name, u.full_name as author_name 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                JOIN User u ON p.user_id = u.user_id 
                WHERE p.status = 'approved' AND p.is_trending = 1 
                ORDER BY p.view_count DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM Category WHERE parent_id IS NULL ORDER BY name";
        return $this->conn->query($sql)->fetchAll();
    }

    public function getPostById($postId)
    {
        // Lấy danh mục 2 cấp
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       parent.name as parent_category_name,
                       u.full_name as author_name, u.avatar 
                FROM Post p 
                JOIN Category c ON p.category_id = c.category_id 
                LEFT JOIN Category parent ON c.parent_id = parent.category_id
                JOIN `User` u ON p.user_id = u.user_id 
                WHERE p.post_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPostTags($postId)
    {
        $sql = "SELECT t.slug FROM Tag t
                JOIN Post_tag pt ON t.tag_id = pt.tag_id
                WHERE pt.post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecommendedPosts($currentPostId)
    {
        $sql = "SELECT p.post_id, p.title, p.thumbnail_URL, p.summary, p.published_at, c.name AS category_name,
                (COALESCE((SELECT COUNT(*) FROM `Like` WHERE post_id = p.post_id), 0) + 
                 COALESCE((SELECT COUNT(*) FROM Comment WHERE post_id = p.post_id), 0) +
                 COALESCE((SELECT COUNT(*) FROM Bookmark WHERE post_id = p.post_id), 0)) as total_interactions
                FROM Post p
                JOIN Category c ON p.category_id = c.category_id
                WHERE p.status = 'approved' AND p.post_id != :current_post_id
                ORDER BY total_interactions DESC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['current_post_id' => $currentPostId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentsByPostId($postId, $limit = 5, $offset = 0)
    {
        $sql = "SELECT c.*, COALESCE(u.full_name, 'client16') AS full_name, u.avatar 
                FROM Comment c
                JOIN `User` u ON c.user_id = u.user_id
                WHERE c.post_id = :post_id AND c.parent_id IS NULL AND c.deleted_at IS NULL
                ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotalCommentsByPostId($postId)
    {
        $sql = "SELECT COUNT(*) FROM Comment WHERE post_id = :post_id AND parent_id IS NULL AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchColumn();
    }

    /**
     * Hàm hỗ trợ tự động sinh mã ID (LK0001, BM0001, CM0001)
     */
    private function generateNewId($tableName, $idColumn, $prefix)
    {
        $sql = "SELECT $idColumn FROM `$tableName` ORDER BY $idColumn DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $lastRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastRecord && !empty($lastRecord[$idColumn])) {
            $lastId = $lastRecord[$idColumn];
            $number = (int) substr($lastId, 2);
            $newNumber = $number + 1;
            return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            return $prefix . '0001';
        }
    }

    public function toggleLike($postId, $userId)
    {
        $sql = "
        SELECT like_id
        FROM `Like`
        WHERE post_id = :post_id
        AND user_id = :user_id
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId
        ]);

        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu đã like -> unlike
        if ($exists) {

            $deleteSql = "
            DELETE FROM `Like`
            WHERE post_id = :post_id
            AND user_id = :user_id
        ";

            $deleteStmt = $this->conn->prepare($deleteSql);

            $deleteStmt->execute([
                ':post_id' => $postId,
                ':user_id' => $userId
            ]);

            return [
                'success' => true,
                'action' => 'unliked'
            ];
        }

    // Nếu chưa like -> insert
    $newLikeId = $this->generateNewId('Like', 'like_id', 'LK');

    $insertSql = "INSERT INTO `Like` (like_id, post_id, user_id, created_at)
                  VALUES (:like_id, :post_id, :user_id, NOW())";
    $insertStmt = $this->conn->prepare($insertSql);
    $insertStmt->execute([
        ':like_id' => $newLikeId,
        ':post_id' => $postId,
        ':user_id' => $userId
    ]);
    return ['success' => true, 'action' => 'liked'];
}

    public function toggleBookmark($postId, $userId)
    {
        $sql = "
        SELECT bookmark_id
        FROM Bookmark
        WHERE post_id = :post_id
        AND user_id = :user_id
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId
        ]);

        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu đã lưu -> bỏ lưu
        if ($exists) {

            $deleteSql = "
            DELETE FROM Bookmark
            WHERE post_id = :post_id
            AND user_id = :user_id
        ";

            $deleteStmt = $this->conn->prepare($deleteSql);

            $deleteStmt->execute([
                ':post_id' => $postId,
                ':user_id' => $userId
            ]);

            return [
                'success' => true,
                'action' => 'unsaved'
            ];
        }

    // Nếu chưa lưu -> insert
     $newBookmarkId = $this->generateNewId('Bookmark', 'bookmark_id', 'BM');

    $insertSql = "INSERT INTO Bookmark (bookmark_id, post_id, user_id, saved_at)
                  VALUES (:bookmark_id, :post_id, :user_id, NOW())";
    $insertStmt = $this->conn->prepare($insertSql);
    $insertStmt->execute([
        ':bookmark_id' => $newBookmarkId,
        ':post_id'     => $postId,
        ':user_id'     => $userId
    ]);

    return ['success' => true, 'action' => 'saved'];
}

    public function isBookmarked($postId, $userId)
    {
        $sql = "
        SELECT bookmark_id
        FROM Bookmark
        WHERE post_id = :post_id
        AND user_id = :user_id
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
    public function isLiked($postId, $userId)
    {
        $sql = "
        SELECT like_id 
        FROM `Like`
        WHERE post_id = :post_id
        AND user_id = :user_id
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);
    

    $stmt->execute([
        'post_id' => $postId,
        'user_id' => $userId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
public function countSavedPostsByUser($userId)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM Bookmark
        WHERE user_id = :user_id
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    public function getSavedPostsByUser($userId, $limit, $offset)
    {
        $sql = "
        SELECT 
            p.post_id,
            p.title,
            p.content,
            p.summary,
            p.thumbnail_URL,
            p.category_id,
            b.saved_at,

            c.name AS category_name,
            c.slug AS category_slug,

            parent.name AS parent_category_name,
            parent.slug AS parent_category_slug

        FROM Bookmark b

        INNER JOIN Post p 
            ON b.post_id = p.post_id

        LEFT JOIN Category c 
            ON p.category_id = c.category_id

        LEFT JOIN Category parent 
            ON c.parent_id = parent.category_id

        WHERE b.user_id = :user_id

        ORDER BY b.saved_at DESC

        LIMIT :limit OFFSET :offset
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addComment($postId, $userId, $content)
    {
        // Tự động sinh mã
        $newCommentId = $this->generateNewId('Comment', 'comment_id', 'CM');

        $sql = "INSERT INTO Comment (comment_id, post_id, user_id, content, created_at) VALUES (:comment_id, :post_id, :user_id, :content, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'comment_id' => $newCommentId,
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $content
        ]);
    }
// Hàm đếm tổng số bài viết của một người dùng với các bộ lọc nâng cao
   public function countMyPostsByUser($userId, $keyword = '', $category = '', $status = '', $date = '')
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM Post p
        LEFT JOIN Category c ON p.category_id = c.category_id
        LEFT JOIN Category parent ON c.parent_id = parent.category_id
        WHERE p.user_id = :user_id
    ";

    $params = [
        ':user_id' => $userId
    ];

    if ($keyword !== '') {
        $sql .= " AND (p.title LIKE :keyword OR p.summary LIKE :keyword OR p.content LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }

   if ($category !== '') {
    $sql .= " AND c.name = :category";
    $params[':category'] = $category;
}

    if ($status !== '') {
        $sql .= " AND p.status = :status";
        $params[':status'] = $status;
    }

    if ($date !== '') {
        $sql .= " AND DATE(p.created_at) = :date";
        $params[':date'] = $date;
    }

    $stmt = $this->conn->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int)($row['total'] ?? 0);
}


public function getMyPostsByUser($userId, $limit, $offset, $keyword = '', $category = '', $status = '', $date = '')
{
    $sql = "
        SELECT
            p.post_id,
            p.title,
            p.summary,
            p.content,
            p.status,
            p.created_at,
            p.updated_at,
            p.view_count,
            p.thumbnail_URL,

            c.name AS category_name,
            parent.name AS parent_category_name

        FROM Post p

        LEFT JOIN Category c
            ON p.category_id = c.category_id

        LEFT JOIN Category parent
            ON c.parent_id = parent.category_id

        WHERE p.user_id = :user_id
    ";

    $params = [
        ':user_id' => $userId
    ];

    if ($keyword !== '') {
        $sql .= " AND (p.title LIKE :keyword OR p.summary LIKE :keyword OR p.content LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }

    if ($category !== '') {
    $sql .= " AND c.name = :category";
    $params[':category'] = $category;
}

    if ($status !== '') {
        $sql .= " AND p.status = :status";
        $params[':status'] = $status;
    }

    if ($date !== '') {
        $sql .= " AND DATE(p.created_at) = :date";
        $params[':date'] = $date;
    }

    $sql .= "
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $this->conn->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm đếm số bài viết của một người dùng theo trạng thái (pending, draft)

public function countMyPostsByUserAndStatus($userId, $status)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM Post
        WHERE user_id = :user_id
        AND status = :status
    ";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(':user_id', $userId);
    $stmt->bindValue(':status', $status);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int)($row['total'] ?? 0);
}



    /*Quản lý bài viết admin */
    public function getAdminPosts($filters, $limit, $offset)
    {
        $where = ["r.role_name = 'admin'"];
        $params = [];

        if (!empty($filters['keyword'])) {
            $where[] = "(p.title LIKE :kw1 OR u.full_name LIKE :kw2)";
            $params['kw1'] = '%' . $filters['keyword'] . '%';
            $params['kw2'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['category_id'])) {
            $where[] = "(c.category_id = :category_id OR c.parent_id = :category_id)";
            $params['category_id'] = $filters['category_id'];
        }
        if (!empty($filters['status'])) {
            $where[] = "p.status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['date'])) {
            $where[] = "DATE(p.created_at) = :date";
            $params['date'] = $filters['date'];
        }

        $whereSQL = implode(' AND ', $where);

        $sql = "SELECT p.*, u.full_name AS author_name,
                   c.name AS category_name, c.parent_id,
                   cp.name AS parent_category_name
            FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            JOIN Category c ON p.category_id = c.category_id
            LEFT JOIN Category cp ON c.parent_id = cp.category_id
            WHERE $whereSQL
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];
        foreach ($stmt->fetchAll() as $row) {
            $posts[] = new Post($row);
        }
        return $posts;
    }

    public function countAdminPosts()
    {
        $sql = "SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'admin'";
        return $this->conn->query($sql)->fetch()['total'];
    }


    // Hàm đếm số bài viết của admin với các bộ lọc nâng cao
    public function countAdminPostsFiltered($filters)
    {
        $where = ["r.role_name = 'admin'"];
        $params = [];

        if (!empty($filters['keyword'])) {
            $where[] = "(p.title LIKE :kw1 OR u.full_name LIKE :kw2)";
            $params['kw1'] = '%' . $filters['keyword'] . '%';
            $params['kw2'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['category_id'])) {
            $where[] = "(c.category_id = :category_id OR c.parent_id = :category_id)";
            $params['category_id'] = $filters['category_id'];
        }
        if (!empty($filters['status'])) {
            $where[] = "p.status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['date'])) {
            $where[] = "DATE(p.created_at) = :date";
            $params['date'] = $filters['date'];
        }

        $whereSQL = implode(' AND ', $where);
        $sql = "SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            JOIN Category c ON p.category_id = c.category_id
            WHERE $whereSQL";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    public function countAdminPostsByStatus($status)
    {
        $sql = "SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'admin' AND p.status = :status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetch()['total'];
    }

    public function countTrendingAdminPosts()
    {
        $sql = "SELECT COUNT(*) AS total FROM Post p
            JOIN `User` u ON p.user_id = u.user_id
            JOIN Role r ON u.role_id = r.role_id
            WHERE r.role_name = 'admin' AND p.is_trending = TRUE";
        return $this->conn->query($sql)->fetch()['total'];
    }
    public function unhidePost($postId)
    {
        $sql = "UPDATE Post SET status = 'approved' WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['post_id' => $postId]);
    }
    public function createPost($data)
    {
        // Sinh post_id mới
        $stmt = $this->conn->query("SELECT post_id FROM Post ORDER BY post_id DESC LIMIT 1");
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        $newNum = $last ? (int) substr($last['post_id'], 2) + 1 : 1;
        $postId = 'PS' . str_pad($newNum, 4, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO Post 
                (post_id, title, summary, content, category_id, user_id, 
                 thumbnail_URL, status, published_at, created_at)
            VALUES 
                (:post_id, :title, :summary, :content, :category_id, :user_id,
                 :thumbnail_url, :status, :published_at, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':post_id' => $postId,
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':category_id' => $data['category_id'],
            ':user_id' => $data['author_id'],
            ':thumbnail_url' => $data['thumbnail_url'],
            ':status' => $data['status'],
            ':published_at' => $data['publish_at'],
        ]);

        // Thêm tags nếu có
        if (!empty($data['tags'])) {
            foreach ($data['tags'] as $tagSlug) {
                $tagSlug = trim($tagSlug);
                if (!$tagSlug)
                    continue;

                // Tìm hoặc tạo tag
                $tagStmt = $this->conn->prepare("SELECT tag_id FROM Tag WHERE slug = :slug LIMIT 1");
                $tagStmt->execute([':slug' => $tagSlug]);
                $tag = $tagStmt->fetch(PDO::FETCH_ASSOC);

                if (!$tag) {
                    $lastTag = $this->conn->query("SELECT tag_id FROM Tag ORDER BY tag_id DESC LIMIT 1")->fetch();
                    $newTagNum = $lastTag ? (int) substr($lastTag['tag_id'], 2) + 1 : 1;
                    $tagId = 'TG' . str_pad($newTagNum, 4, '0', STR_PAD_LEFT);
                    $this->conn->prepare("INSERT INTO Tag (tag_id, slug) VALUES (:id, :slug)")
                        ->execute([':id' => $tagId, ':slug' => $tagSlug]);
                } else {
                    $tagId = $tag['tag_id'];
                }

                $this->conn->prepare("INSERT IGNORE INTO Post_tag (post_id, tag_id) VALUES (:post_id, :tag_id)")
                    ->execute([':post_id' => $postId, ':tag_id' => $tagId]);
            }
        }

        return $postId;
    }
}
