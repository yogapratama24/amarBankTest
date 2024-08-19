<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\LoanRepository;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanService
{
    private $repository;

    public function __construct(LoanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): string
    {
        $this->validateUserData($data);

        if ($data['sex'] === 'F') {
            $day = intval(substr($data['ktp'], 6, 2));
            $day += 40;
            $data['ktp'] = substr($data['ktp'], 0, 6) . (string)$day. substr($data['ktp'], 8, 8);
        }

        $id = $this->repository->create($data);

        $logDirectory = APP_ROOT . '/logs'; // Adjust path as needed
        $logFilePath = $logDirectory . '/submissions.json';

        // Log the successful submission
        $logData = [
            'id' => $id,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Ensure the directory exists
        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }

        // Read existing log data from file
        if (file_exists($logFilePath)) {
            $existingData = json_decode(file_get_contents($logFilePath), true);
            if (is_array($existingData)) {
                $existingData[] = $logData;
            } else {
                $existingData = [$logData];
            }
        } else {
            $existingData = [$logData];
        }

        // Write updated log data to file
        file_put_contents($logFilePath, json_encode($existingData, JSON_PRETTY_PRINT));

        return $id;
    }

    private function validateUserData(array $data)
    {
        $nameValidator = v::stringType()
            ->notEmpty()
            ->length(1, 100)
            ->callback(function($name) {
                if (count(explode(' ', $name)) < 2) {
                    return false;
                }
                return true;
            });

        $ktpValidator = v::stringType()->notEmpty()->length(16, 16)
            ->callback(function($ktp) use ($data) {
                $dob = $data['date_of_birth'];
                $dob = \DateTime::createFromFormat('Y-m-d', $dob);
                $dobFormatted = $dob ? $dob->format('dmy') : '';

                if ($dobFormatted === '') {
                    return false;
                }
                
                // Extract components from the KTP number
                $day = substr($ktp, 6, 2);
                $month = substr($ktp, 8, 2);
                $year = substr($ktp, 10, 2);
                $dobFromKTP = $day . $month . $year;

                if ($dobFromKTP != $dobFormatted) {
                    return false;
                }

                return true;
            });

            $loanPurposeValidator = v::stringType()->notEmpty()
                ->callback(function($purpose) {
                    $keywords = ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'];
                    foreach ($keywords as $keyword) {
                        if (stripos($purpose, $keyword) !== false) {
                            return true;
                        }
                    }
                    return false;
                })->setTemplate('Purpose must include at least one of the following words: vacation, renovation, electronics, wedding, rent, car, investment.');

            

        $validation = v::arrayType()
        ->key('name', $nameValidator)
        ->key('ktp', $ktpValidator)
        ->key('loan_amount', v::intType()->min(1000)->max(10000))
        ->key('loan_period', v::intType()->min(1)->max(24))
        ->key('loan_purpose', $loanPurposeValidator)
        ->key('sex', v::stringType()->notEmpty()->in(['M', 'F']))
        ->key('date_of_birth', v::date());
                
        $validation->assert($data);
    }

    public function formatValidationErrors(NestedValidationException $exception): array
    {
        $errors = [];
        foreach ($exception->getMessages() as $field => $message) {
            if ($field === 'name') {
                $errors[$field] = 'The name must include at least two names (first and last).';
            } else if ($field === 'ktp') {
                $errors[$field] = 'Invalid KTP format. Ensure it matches the correct pattern for your date of birth.';
            } else {
                $errors[$field] = $message;
            }
        }
        return $errors;
    }
}