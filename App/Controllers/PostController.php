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

    public function homepage() {
        $repo = new PostRepository();
        
        // Lấy dữ liệu từ TiDB
        $heroPost = $repo->getHeroPost();
        $thoiSu = $repo->getPostsByCategory('Thời sự', 4);

        // Gọi View trang chủ
        require_once __DIR__ . '/../Views/Client/Home.php';
    }
}