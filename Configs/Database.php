<?php

class Database
{
    // Khai báo các thuộc tính để lưu trữ thông tin kết nối
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    // Dùng hàm khởi tạo __construct để lấy dữ liệu từ file .env
    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $this->port = $_ENV['DB_PORT'] ?? 3306;
        $this->dbname = $_ENV['DB_NAME'] ?? '';
        $this->username = $_ENV['DB_USERNAME'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
    }

    public function connect()
{
    try {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
            $this->username,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_CA => dirname($_SERVER['SCRIPT_FILENAME']) . '/CA.pem'
            ]
        );
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
}