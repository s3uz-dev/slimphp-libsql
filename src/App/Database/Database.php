<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

final class Database
{
    private PDO $pdo;

    public function __construct(
        private string $host ,
        private string $port ,
        private string $dbName ,
        private string $username ,
        private string $password 
    ) { 
       
        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
