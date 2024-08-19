<?php

use App\Database;

return [
    Database::class => function() {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        return new Database($host, $dbname, $user, $password);
    }
];