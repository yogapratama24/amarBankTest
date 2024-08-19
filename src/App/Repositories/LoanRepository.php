<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class LoanRepository
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO loans (name, ktp, loan_amount, loan_period, loan_purpose, sex, date_of_birth)
                VALUES (:name, :ktp, :loan_amount, :loan_period, :loan_purpose, :sex, :date_of_birth)";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':ktp', $data['ktp'], PDO::PARAM_STR);
        $stmt->bindValue(':loan_amount', $data['loan_amount'], PDO::PARAM_INT);
        $stmt->bindValue(':loan_period', $data['loan_period'], PDO::PARAM_INT);
        $stmt->bindValue(':loan_purpose', $data['loan_purpose'], PDO::PARAM_STR);
        $stmt->bindValue(':sex', $data['sex'], PDO::PARAM_STR);
        $stmt->bindValue(':date_of_birth', $data['date_of_birth'], PDO::PARAM_STR);

        $stmt->execute();

        return $pdo->lastInsertId();
    }
}