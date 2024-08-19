<?php

declare(strict_types=1);

namespace App\Services;

use PHPUnit\Framework\TestCase;
use App\Repositories\LoanRepository;
use App\Database;
use PDO;
use PDOStatement;

class LoanRepositoryTest extends TestCase
{
    private $pdoMock;
    private $databaseMock;
    private $loanRepository;

    protected function setUp(): void
    {
       // Create a mock for the PDO class
       $this->pdoMock = $this->createMock(PDO::class);

       // Create a mock for the Database class
       $this->databaseMock = $this->getMockBuilder(Database::class)
           ->disableOriginalConstructor() // if needed, disable the original constructor
           ->getMock();

       // Ensure the getConnection method returns the PDO mock
       $this->databaseMock->method('getConnection')->willReturn($this->pdoMock);

       // Pass the Database mock to the LoanRepository
       $this->loanRepository = new LoanRepository($this->databaseMock);
    }

    public function testCreateUser()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->pdoMock->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('1');

        $userData = [
            'name' => 'John Doe',
            'ktp' => '3202161202990008',
            'loan_amount' => 10000,
            'loan_period' => 12,
            'loan_purpose' => 'vacation',
            'sex' => 'M',
            'date_of_birth' => '1999-02-12'
        ];

        $result = $this->loanRepository->create($userData);

        $this->assertEquals('1', $result);
    }
}