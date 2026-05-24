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
$router->get('admin_posts',      [PostController::class, 'adminPosts']);

// ── Profile Admin ───────────────────────────────────────────────────────────
$router->post('update-profile',  [ProfileController::class, 'updateProfile']);
$router->post('change_password', [ProfileController::class, 'changePassword']);