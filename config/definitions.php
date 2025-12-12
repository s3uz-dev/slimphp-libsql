<?php
declare(strict_types=1);

use App\Database\Database;

return [
    Database::class => function () {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbName = getenv('DB_DATABASE') ?: 'slim_db';
        $username = getenv('DB_USERNAME') ?: 's3uz';
        $password = getenv('DB_PASSWORD') ?: 'masterpass';

        return new Database(
            $host,
            $port,
            $dbName,
            $username,
            $password
        );
    },
];