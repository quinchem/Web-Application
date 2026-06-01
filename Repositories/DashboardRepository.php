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
                    name,
                    parent_id
                FROM Category
                ORDER BY name ASC
            ";

            $stmt = $this->conn->prepare($sql);
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
    // totalPosts — toàn hệ thống, không lọc
    $totalStmt = $this->conn->prepare("
        SELECT COUNT(*) as total FROM Post WHERE deleted_at IS NULL
    ");
    $totalStmt->execute();
    $totalPosts = $totalStmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

    // pendingPosts — trạng thái hiện tại, không lọc
    $pendingStmt = $this->conn->prepare("
        SELECT COUNT(*) as total FROM Post
        WHERE deleted_at IS NULL AND status = 'pending'
    ");
    $pendingStmt->execute();
    $pendingPosts = $pendingStmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

    // totalAuthors — toàn hệ thống, không lọc
    $readerStmt = $this->conn->prepare("
        SELECT COUNT(*) as total FROM User WHERE role_id = 'RL0002'
    ");
    $readerStmt->execute();
    $totalAuthors = $readerStmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

    // totalViews — lọc theo filter
    $where  = ["p.deleted_at IS NULL"];
    $params = [];

    if ($fromDate) {
        $where[]           = "DATE(p.created_at) >= :fromDate";
        $params[':fromDate'] = $fromDate;
    }
    if ($toDate) {
        $where[]         = "DATE(p.created_at) <= :toDate";
        $params[':toDate'] = $toDate;
    }
    if ($category) {
        $where[]           = "p.category_id = :category";
        $params[':category'] = $category;
    }
    if ($author) {
        $where[]         = "u.full_name LIKE :author";
        $params[':author'] = "%$author%";
    }

    $whereSql = 'WHERE ' . implode(' AND ', $where);

    $viewStmt = $this->conn->prepare("
        SELECT COALESCE(SUM(p.view_count), 0) as totalViews
        FROM Post p
        LEFT JOIN User u ON p.user_id = u.user_id
        $whereSql
    ");
    $viewStmt->execute($params);
    $totalViews = $viewStmt->fetch(\PDO::FETCH_ASSOC)['totalViews'] ?? 0;

    return [
        'totalPosts'   => $totalPosts,
        'pendingPosts' => $pendingPosts,
        'totalAuthors' => $totalAuthors,
        'totalViews'   => $totalViews,
        'hasDateFilter' => !empty($fromDate) || !empty($toDate),
        'fromDate'      => $fromDate,
        'toDate'        => $toDate,
    ];
}

    // =========================
    // POST CHART
    // =========================

public function getPostChart(
    $fromDate = null,
    $toDate   = null,
    $category = null,
    $author   = null
) {

    // Tính khoảng cách để quyết định group theo ngày/tháng/năm
    $from = $fromDate ? new \DateTime($fromDate) : null;
    $to   = $toDate   ? new \DateTime($toDate)   : null;

    $groupBy    = 'day';
    $dateSelect = 'DATE(p.created_at)';
    $dateGroup  = 'DATE(p.created_at)';
    $defaultFrom = null;

    if (!$fromDate && !$toDate) {
        $groupBy     = 'month';
        $dateSelect  = "DATE_FORMAT(p.created_at, '%Y-%m')";
        $dateGroup   = "DATE_FORMAT(p.created_at, '%Y-%m')";
        $defaultFrom = date('Y-m-d', strtotime('-12 months'));
    } elseif ($from && $to) {
        $diffDays = $from->diff($to)->days;
        if ($diffDays > 365) {
            $groupBy    = 'year';
            $dateSelect = 'YEAR(p.created_at)';
            $dateGroup  = 'YEAR(p.created_at)';
        } elseif ($diffDays > 90) {
            $groupBy    = 'month';
            $dateSelect = "DATE_FORMAT(p.created_at, '%Y-%m')";
            $dateGroup  = "DATE_FORMAT(p.created_at, '%Y-%m')";
        }
    }

            $where = [
            "p.deleted_at IS NULL",
            "p.status = 'approved'" 
        ];
    $params = [];

    $effectiveFrom = $fromDate ?: $defaultFrom;

    if ($effectiveFrom) {
        $where[]  = "DATE(p.created_at) >= ?";
        $params[] = $effectiveFrom;
    }
    if ($toDate) {
        $where[]  = "DATE(p.created_at) <= ?";
        $params[] = $toDate;
    }
    if ($category) {
        $where[]  = "p.category_id = ?";
        $params[] = $category;
    }
    if ($author) {
        $where[]  = "u.full_name LIKE ?";
        $params[] = "%$author%";
    }

    $whereSql = 'WHERE ' . implode(' AND ', $where);

    $stmt = $this->conn->prepare("
        SELECT
            {$dateSelect} as post_date,
            COUNT(*)      as total
        FROM Post p
        LEFT JOIN User u ON p.user_id = u.user_id
        {$whereSql}
        GROUP BY {$dateGroup}
        ORDER BY {$dateGroup} ASC
    ");
    $stmt->execute($params);

    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return [
        'groupBy' => $groupBy,
        'data'    => $rows
    ];
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
    $where  = ["p.deleted_at IS NULL"];
    $params = []; 
    if ($fromDate) { $where[] = "DATE(p.created_at) >= ?"; $params[] = $fromDate; }
    if ($toDate)   { $where[] = "DATE(p.created_at) <= ?"; $params[] = $toDate;   }

    if ($category) {
        $where[]   = "p.category_id = ?";
        $params[]  = $category;
    }
    if ($author) {
        $where[]   = "u.full_name LIKE ?";
        $params[]  = "%$author%";
    }

    $whereSql = 'WHERE ' . implode(' AND ', $where);

    $stmt = $this->conn->prepare("
        SELECT p.status, COUNT(*) as total
        FROM Post p
        LEFT JOIN User u ON p.user_id = u.user_id
        $whereSql
        GROUP BY p.status
    ");
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

    $convert = function($date) {
        if (!$date) return null;
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            $parts = explode('/', $date);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return $date;
    };
    $fromDate = $convert($fromDate);
    $toDate   = $convert($toDate);

    // Mặc định 30 ngày gần nhất nếu không có date filter
    if (!$fromDate && !$toDate) {
        $fromDate = date('Y-m-d', strtotime('-30 days'));
    }

    $where  = ["p.deleted_at IS NULL", "p.status = 'approved'"];
    $params = [];

    if ($fromDate) {
        $where[]  = "DATE(p.created_at) >= ?";
        $params[] = $fromDate;
    }
    if ($toDate) {
        $where[]  = "DATE(p.created_at) <= ?";
        $params[] = $toDate;
    }
    if ($category) {
        $where[]  = "p.category_id = ?";
        $params[] = $category;
    }
    if ($author) {
        $where[]  = "u.full_name LIKE ?";
        $params[] = "%$author%";
    }

    $whereSql = 'WHERE ' . implode(' AND ', $where);

    $stmt = $this->conn->prepare("
        SELECT
            p.post_id, p.title,
            c.name AS category_name,
            p.view_count,
            COUNT(DISTINCT l.like_id)    AS likes_count,
            COUNT(DISTINCT cm.comment_id) AS comments_count
        FROM Post p
        LEFT JOIN Category c ON p.category_id = c.category_id
        LEFT JOIN `Like`    l  ON p.post_id = l.post_id
        LEFT JOIN Comment   cm ON p.post_id = cm.post_id
        LEFT JOIN User      u  ON p.user_id = u.user_id
        $whereSql
        GROUP BY p.post_id, p.title, c.name, p.view_count
        ORDER BY p.view_count DESC
        LIMIT 5
    ");
    $stmt->execute($params);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
}