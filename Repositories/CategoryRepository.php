<?php
// Repositories/CategoryRepository.php

require_once __DIR__ . '/../Configs/Database.php';
require_once __DIR__ . '/../App/Models/Category.php';

class CategoryRepository
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getCategories()
    {
        $sql = "
            SELECT
                parent.category_id AS parent_id,
                parent.name AS parent_name,
                parent.slug AS parent_slug,

                child.category_id AS child_id,
                child.name AS child_name,
                child.slug AS child_slug

            FROM Category parent

            INNER JOIN Category child
                ON child.parent_id = parent.category_id

            WHERE parent.name IN ('Thời sự', 'Kinh tế')

            ORDER BY
                FIELD(parent.name, 'Thời sự', 'Kinh tế'),
                FIELD(child.name,
                    'Quân sự', 'Chính trị', 'Xã hội',
                    'Thị trường', 'Chứng khoán', 'Doanh nghiệp', 'Ngân hàng'
                )
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
