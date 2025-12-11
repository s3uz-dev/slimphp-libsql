<?php 

declare(strict_types=1);    

use App\Database;

return [
    Database::class => function () {
        return new Database(
            host: '127.0.0.1',
            dbName: 'slim_db',
            username: 's3uz',
            password: 'masterpass'
        );
    },
];