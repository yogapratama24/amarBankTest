<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private $host;
    private $name;
    private $user;
    private $password;

    public function __construct(string $host, string $name, string $user, string $password)
    {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        $dsn = "pgsql:host=$this->host;dbname=$this->name";
        $pdo = new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $pdo;
    }
}