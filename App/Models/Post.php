<?php
/**
 * Post.php - Model Bài viết
 * 
 * Đại diện cho một bài viết trong hệ thống
 * Chứa tất cả thông tin liên quan đến bài viết
 */

/**
 * Lớp Post
 * Đối tượng dữ liệu để lưu trữ thông tin bài viết
 */
class Post
{
    // Thuộc tính dữ liệu của bài viết
    public $post_id;                // ID bài viết (khóa chính)
    public $title;                  // Tiêu đề bài viết
    public $status;                 // Trạng thái (approved, pending, rejected)
    public $view_count;             // Số lượt xem
    public $published_at;           // Ngày công bố
    public $created_at;             // Ngày tạo
    public $is_trending;            // Có phải bài viết xu hướng không (1 = có, 0 = không)
    public $author_name;            // Tên tác giả
    public $category_name;          // Tên danh mục
    public $parent_category_name;   // Tên danh mục cha
    public $thumbnail_URL;          // Đường dẫn ảnh đại diện
    public $summary;                // Tóm tắt/mô tả ngắn bài viết
    public $content;                // Nội dung đầy đủ bài viết
    public $user_id;                // ID người dùng (tác giả)
    public $category_id;            // ID danh mục

    /**
     * Constructor - Khởi tạo đối tượng Post
     * 
     * @param array $data Mảng dữ liệu từ database
     */
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
        $this->thumbnail_URL = $data['thumbnail_URL'] ?? '';
        $this->summary = $data['summary'] ?? '';
        $this->content = $data['content'] ?? '';
        $this->user_id = $data['user_id'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
    }
    
}