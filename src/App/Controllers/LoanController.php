<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\LoanService;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanController
{
    private $service;

    public function __construct(LoanService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        try {
            $id = $this->service->createUser($data);
            $response->getBody()->write(json_encode([
                "message" => "Successfully created loan",
                "id" => $id
            ]));

            return $response->withHeader("Content-Type", "application/json")->withStatus(201);
        } catch (NestedValidationException $e) {
            $errors = $this->service->formatValidationErrors($e);
            $response->getBody()->write(json_encode([
                'status' => 'error', 
                'errors' => $errors, 
                "message" => "your request is incomplete"
            ]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(400);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]));

            return $response->withHeader("Content-Type", "application/json")->withStatus(500);
        }
    }
}