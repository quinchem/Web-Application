<?php
// App/Models/Category.php

class Category
{
    public $category_id;
    public $name;
    public $parent_id;
    public $order;
    public $slug;

    public function __construct($data = [])
    {
        $this->category_id = $data['category_id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->parent_id = $data['parent_id'] ?? null;
        $this->order = $data['order'] ?? null;
        $this->slug = $data['slug'] ?? '';
    }
}