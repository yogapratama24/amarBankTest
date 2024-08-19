<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\LoanController;
use PHPUnit\Framework\TestCase;
use App\Services\LoanService;
use Psr\Http\Message\RequestInterface as Request;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanControllerTest extends TestCase
{
    private $loanServiceMock;
    private $loanController;

    protected function setUp(): void
    {
        $this->loanServiceMock = $this->createMock(LoanService::class);
        $this->loanController = new LoanController($this->loanServiceMock);
    }

    public function testCreate()
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

        $this->loanServiceMock->expects($this->once())
            ->method('createUser')
            ->with($userData)
            ->willReturn('1');

        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn(Stream::create(json_encode($userData)));

        $response = new Response();

        $result = $this->loanController->create($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertJson($result->getBody()->__toString());
    }

    public function testCreateValidationFailure()
    {
        // Create a mock NestedValidationException with default constructor
        $validationException = $this->getMockBuilder(NestedValidationException::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loanServiceMock->expects($this->once())
            ->method('createUser')
            ->willThrowException($validationException);

        // Assuming that formatValidationErrors method is called with $validationException
        $this->loanServiceMock->expects($this->once())
            ->method('formatValidationErrors')
            ->with($validationException)
            ->willReturn(['Invalid data']);

        $request = $this->createMock(Request::class);
        $request->method('getBody')
            ->willReturn(Stream::create(json_encode([])));

        $response = new Response();

        $result = $this->loanController->create($request, $response);

        $this->assertEquals(400, $result->getStatusCode());
        $responseBody = json_decode($result->getBody()->__toString(), true);
        $this->assertEquals('error', $responseBody['status']);
        $this->assertEquals(['Invalid data'], $responseBody['errors']);
    }
}