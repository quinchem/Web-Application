<?php

require_once __DIR__ . '/../App/Controllers/PostController.php';

$postController = new PostController();

if ($page === 'admin_user_posts') {
    $postController->adminUserPosts();
}