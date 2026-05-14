<?php

class Database
{
    private $host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
    private $port = 4000;
    private $dbname = "db_tramtinviet";
    private $username = "3G2qrmUU6dQWYxU.root";
    private $password = "dgDxS8gZjN6G2owp";

    public function connect()
    {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
            $this->username,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/../CA.pem'
            ]
        );
    }
}