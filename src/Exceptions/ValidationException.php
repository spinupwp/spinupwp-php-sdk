<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected array $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Invalid or missing parameters.', 422);

        $this->errors = $errors;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}