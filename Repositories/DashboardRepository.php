<?php
// Repositories/DashboardRepository.php

require_once __DIR__ .
'/../Configs/Database.php';

class DashboardRepository
{
    private $conn;

    public function __construct()
    {
        $database =
        new Database();

        $this->conn =
        $database->connect();
    }

    // =========================
    // CATEGORY
    // =========================

    public function getCategories()
    {
        $sql = "
            SELECT
                category_id,
                name
            FROM Category
            ORDER BY name ASC
        ";

        $stmt =
        $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // =========================
    // KPI
    // =========================

    public function getKPI(
        $fromDate = null,
        $toDate = null,
        $category = null,
        $author = null
    ) {

        $where  = [];
        $params = [];

        if ($fromDate) {

            $where[] =
            "DATE(p.created_at) >= :fromDate";

            $params[':fromDate'] =
            $fromDate;
        }

        if ($toDate) {

            $where[] =
            "DATE(p.created_at) <= :toDate";

            $params[':toDate'] =
            $toDate;
        }

        if ($category) {

            $where[] =
            "p.category_id = :category";

            $params[':category'] =
            $category;
        }

        if ($author) {

            $where[] =
            "u.full_name LIKE :author";

            $params[':author'] =
            "%$author%";
        }

        $whereSql =
        count($where)
        ? 'WHERE ' . implode(' AND ', $where)
        : '';

        $sql = "
            SELECT

                COUNT(*) totalPosts,

                SUM(
                    CASE
                        WHEN p.status = 'pending'
                        THEN 1
                        ELSE 0
                    END
                ) pendingPosts,

                SUM(p.view_count) totalViews

            FROM Post p

            INNER JOIN User u
            ON p.user_id = u.user_id

            $whereSql
        ";

        $stmt =
        $this->conn->prepare($sql);

        $stmt->execute($params);

        $result =
        $stmt->fetch(\PDO::FETCH_ASSOC);

        // Đếm người đọc

        $readerSql = "
            SELECT COUNT(*) totalAuthors
            FROM User
            WHERE role_id = 'RL0002'
        ";

        $readerStmt =
        $this->conn->prepare($readerSql);

        $readerStmt->execute();

        $reader =
        $readerStmt->fetch(\PDO::FETCH_ASSOC);

        return [

            'totalPosts' =>
            $result['totalPosts'] ?? 0,

            'pendingPosts' =>
            $result['pendingPosts'] ?? 0,

            'totalViews' =>
            $result['totalViews'] ?? 0,

            'totalAuthors' =>
            $reader['totalAuthors'] ?? 0
        ];
    }

    // =========================
    // POST CHART
    // =========================

public function getPostChart(
     $fromDate = null,
    $toDate = null,
    $category = null,
    $author = null      
) {

    $sql = "

        SELECT

            DATE(created_at) as post_date,

            COUNT(*) as total

        FROM Post

        WHERE deleted_at IS NULL
    ";

    $params = [];

    if ($fromDate) {

        $sql .= " AND DATE(created_at) >= ? ";

        $params[] = $fromDate;
    }

    if ($toDate) {

        $sql .= " AND DATE(created_at) <= ? ";

        $params[] = $toDate;
    }

    $sql .= "

        GROUP BY DATE(created_at)

        ORDER BY DATE(created_at)
    ";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}


// =========================
// STATUS CHART
// =========================

public function getStatusChart(
    $fromDate = null,
    $toDate = null,
    $category = null,
    $author = null
) {

    $sql = "

        SELECT

            status,

            COUNT(*) as total

        FROM Post

        WHERE deleted_at IS NULL
    ";

    $params = [];

    if ($fromDate) {

        $sql .= " AND DATE(created_at) >= ? ";

        $params[] = $fromDate;
    }

    if ($toDate) {

        $sql .= " AND DATE(created_at) <= ? ";

        $params[] = $toDate;
    }

    if ($category) {

        $sql .= " AND category_id = ? ";

        $params[] = $category;
    }

    $sql .= "

        GROUP BY status
    ";

    $stmt =
    $this->conn->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    // =========================
    // TOP POSTS
    // =========================

   public function getTopPosts(
    $fromDate = null,
    $toDate = null,
    $category = null,
    $author = null
) {

    $sql = "

        SELECT

            p.post_id,

            p.title,

            c.name AS category_name,

            p.view_count,

            COUNT(DISTINCT l.like_id) AS likes_count,

            COUNT(DISTINCT cm.comment_id) AS comments_count

        FROM Post p

        LEFT JOIN Category c
        ON p.category_id = c.category_id

        LEFT JOIN `Like` l
        ON p.post_id = l.post_id

        LEFT JOIN Comment cm
        ON p.post_id = cm.post_id

        LEFT JOIN User u
        ON p.user_id = u.user_id

        WHERE p.deleted_at IS NULL
    ";

    $params = [];

    if ($fromDate) {

        $sql .= "
            AND DATE(p.created_at) >= ?
        ";

        $params[] = $fromDate;
    }

    if ($toDate) {

        $sql .= "
            AND DATE(p.created_at) <= ?
        ";

        $params[] = $toDate;
    }

    if ($category) {

        $sql .= "
            AND p.category_id = ?
        ";

        $params[] = $category;
    }

    if ($author) {

        $sql .= "
            AND u.full_name LIKE ?
        ";

        $params[] = "%$author%";
    }

    $sql .= "

        GROUP BY

            p.post_id,
            p.title,
            c.name,
            p.view_count

        ORDER BY p.view_count DESC

        LIMIT 5
    ";

    $stmt =
    $this->conn->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
}