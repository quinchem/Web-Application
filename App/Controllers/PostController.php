<?php

require_once __DIR__ . '/../../Repositories/PostRepository.php';

class PostController
{
    private $postRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    public function adminUserPosts()
    {
        $posts = $this->postRepository->getUserPostsForAdmin();

        $totalPosts = $this->postRepository->countUserPosts();
        $pendingPosts = $this->postRepository->countUserPostsByStatus('pending');
        $hiddenPosts = $this->postRepository->countUserPostsByStatus('hidden');
        $trendingPosts = $this->postRepository->countTrendingUserPosts();

        require_once __DIR__ . '/../Views/Admin/Post/Index.php';
    }
}