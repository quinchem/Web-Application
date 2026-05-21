<?php

// ✅ ĐÚNG — lên 1 cấp là tới Web-Application/, có Configs/ ở đó
require_once __DIR__ . '/../Configs/Database.php';

// ✅ ĐÚNG — lên 1 cấp rồi vào App/Models/
require_once __DIR__ . '/../App/Models/Client.php';
class ClientRepository
{
    private $conn;

    public function __construct()
    {
        $database = new Database();

        $this->conn = $database->connect();
    }


    // =========================
    // LOGIN
    // =========================

   public function getUserByUsername($username) {
        try {
            // Chú ý: Thay 'users' bằng tên bảng thực tế của bạn trong CSDL nếu khác
            $sql = "SELECT * FROM user WHERE user_name = :username LIMIT 1";
            
            // Nếu bạn dùng PDO:
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            // Trả về một mảng chứa thông tin user, hoặc false nếu không tìm thấy
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            echo "Lỗi truy vấn: " . $e->getMessage();
            return false;
        }
    }
}   
