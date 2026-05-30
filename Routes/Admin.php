<?php
// Routes/Admin.php

use App\Controllers\PostController;
use App\Controllers\ProfileController;

// ── Bài viết công khai ──────────────────────────────────────────────────────
$router->get('category',         [PostController::class, 'category']);
$router->get('post',             [PostController::class, 'post']);
$router->get('hide_post',        [PostController::class, 'hidePost']);

// ── Quản lý bài viết Admin ──────────────────────────────────────────────────
$router->get('admin_user_posts', [PostController::class, 'adminUserPosts']);
$router->get('review_post',      [PostController::class, 'reviewPost']);
$router->get('review_post_page', [PostController::class, 'reviewPostPage']);
$router->get('admin_posts',      [PostController::class, 'adminPosts']);
$router->get('unhide_post', [PostController::class, 'unhidePost']);
$router->get('create_post',  [PostController::class, 'createPost']);
$router->post('store_post',  [PostController::class, 'storePost']);
$router->get('edit_post',        [PostController::class, 'editPost']);
$router->post('update_post',     [PostController::class, 'updatePost']);
$router->post('delete_post',     [PostController::class, 'deletePost']);

// ── Profile Admin ───────────────────────────────────────────────────────────
$router->post('update-profile',  [ProfileController::class, 'updateProfile']);
$router->post('change_password', [ProfileController::class, 'changePassword']);