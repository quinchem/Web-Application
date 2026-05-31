<?php
// store_post.php — Xử lý lưu bài viết (draft hoặc published)

/** @var \PDO $pdo */  // Báo cho Intelephense biết $pdo được inject từ ngoài

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Admin_index.php?page=admin_posts');
    exit;
}

// ── Lấy action: 'draft' hoặc 'publish' ──
$action = $_POST['action'] ?? 'draft';

// ── Map action → status lưu vào DB ──
$status = ($action === 'publish') ? 'published' : 'draft';

// ── Lấy dữ liệu từ form ──
$title       = trim($_POST['title']        ?? '');
$summary     = trim($_POST['summary']      ?? '');
$content     = trim($_POST['content']      ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$publish_at  = !empty($_POST['publish_at']) ? $_POST['publish_at'] : null;
$tags        = $_POST['tags'] ?? [];

// ── Xử lý ảnh đại diện ──
$thumbnail = null;
if (!empty($_FILES['thumbnail']['tmp_name'])) {
    $uploadDir = __DIR__ . '/../../Public/Uploads/Thumbnails/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $ext      = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('thumb_') . '.' . $ext;
    $destPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destPath)) {
        $thumbnail = 'Public/Uploads/Thumbnails/' . $filename;
    }
}

// ── Lấy author_id từ session ──
$author_id = $_SESSION['admin_id'] ?? 1;

try {
    // ── Insert bài viết ──
    $stmt = $pdo->prepare("
        INSERT INTO posts
            (title, summary, content, thumbnail, category_id, author_id, status, publish_at, created_at)
        VALUES
            (:title, :summary, :content, :thumbnail, :category_id, :author_id, :status, :publish_at, NOW())
    ");
    $stmt->execute([
        ':title'       => $title,
        ':summary'     => $summary,
        ':content'     => $content,
        ':thumbnail'   => $thumbnail,
        ':category_id' => $category_id ?: null,
        ':author_id'   => $author_id,
        ':status'      => $status,
        ':publish_at'  => $publish_at,
    ]);

    $postId = $pdo->lastInsertId();

    // ── Insert tags (nếu có) ──
    if (!empty($tags)) {
        $tagStmt   = $pdo->prepare("INSERT IGNORE INTO tags (name) VALUES (:name)");
        $pivotStmt = $pdo->prepare("
            INSERT INTO post_tags (post_id, tag_id)
            SELECT :post_id, id FROM tags WHERE name = :name
        ");

        foreach ($tags as $tag) {
            $tag = trim($tag);
            if ($tag === '') continue;
            $tagStmt->execute([':name' => $tag]);
            $pivotStmt->execute([':post_id' => $postId, ':name' => $tag]);
        }
    }

    // ── Redirect theo kết quả ──
    if ($status === 'published') {
        header('Location: Admin_index.php?page=admin_posts&success=published');
    } else {
        header('Location: Admin_index.php?page=admin_posts&success=draft');
    }
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Lỗi khi lưu bài viết: ' . $e->getMessage();
    header('Location: Admin_index.php?page=post_create');
    exit;
}