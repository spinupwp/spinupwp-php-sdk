<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class TimeoutException extends Exception
{
    protected ?array $output;

    public function __construct(array $output = null)
    {
        parent::__construct('Script timed out while waiting for the event to complete.');

        $this->output = $output;
    }

    public function output(): ?array
    {
        return $this->output;
    }
}