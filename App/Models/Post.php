<?php

class Post
{
    public $post_id;
    public $title;
    public $status;
    public $view_count;
    public $published_at;
    public $created_at;
    public $is_trending;
    public $author_name;
    public $category_name;
    public $parent_category_name;

    public function __construct($data = [])
    {
        $this->post_id = $data['post_id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->view_count = $data['view_count'] ?? 0;
        $this->published_at = $data['published_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->is_trending = $data['is_trending'] ?? 0;
        $this->author_name = $data['author_name'] ?? '';
        $this->category_name = $data['category_name'] ?? '';
        $this->parent_category_name = $data['parent_category_name'] ?? '';
    }
}