<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Database
{
    private PDO $connection;

    public function __construct( 
        private string $host  ,
        private string $dbName  ,
        private string $username  ,
        private string $password )
    {
        $dsn =  "mysql:host=$this->host;dbname=$this->dbName;charset=utf8mb4" ; 
        $this->connection = new PDO($dsn, $this->username, $this->password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
};
