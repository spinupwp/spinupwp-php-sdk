<?php

namespace SpinupWp\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct(array $response)
    {
        parent::__construct($response['message'] ?? 'Bad request.', 400);
    }
}
