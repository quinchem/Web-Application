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

    public function checkLogin($email, $password)
{
    $sql = "
        SELECT *
        FROM `User`
        WHERE email = :email
        AND account_status = 'active'
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        'email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    // CHECK HASH PASSWORD

    if ($user) {

        if (
            password_verify(
                $password,
                $user['password']
            )
        ) {

            return new User($user);
        }
    }

    return null;
}   
}