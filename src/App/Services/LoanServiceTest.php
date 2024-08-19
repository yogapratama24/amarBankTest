<?php

declare(strict_types=1);

namespace App\Services;

use PHPUnit\Framework\TestCase;
use App\Services\UserService;
use App\Repositories\LoanRepository;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanServiceTest extends TestCase
{
    private $loanService;
    private $loanRepositoryMock;

    protected function setUp(): void
    {
        $this->loanRepositoryMock = $this->createMock(LoanRepository::class);
        $this->loanService = new LoanService($this->loanRepositoryMock);
    }

    public function testCreateUserSuccess()
    {
        $userData = [
            'name' => 'John Doe',
            'ktp' => '3202161202990008',
            'loan_amount' => 10000,
            'loan_period' => 12,
            'loan_purpose' => 'vacation',
            'sex' => 'M',
            'date_of_birth' => '1999-02-12'
        ];

        $this->loanRepositoryMock->expects($this->once())
            ->method('create')
            ->with($userData)
            ->willReturn('1');

        $result = $this->loanService->createUser($userData);

        $this->assertEquals('1', $result);
    }

    public function testCreateUserValidationFailure()
    {
        $this->expectException(NestedValidationException::class);

        $userData = [
            'name' => 'John', // Invalid name
            'ktp' => '1234567890123456', // Invalid KTP
            'loan_amount' => 500, // Invalid loan amount
            'loan_period' => 25, // Invalid loan period
            'loan_purpose' => 'unknown', // Invalid loan purpose
            'sex' => 'M',
            'date_of_birth' => '1999-02-12'
        ];

        $this->loanService->createUser($userData);
    }
}