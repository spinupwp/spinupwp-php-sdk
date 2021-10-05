<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Your API token is wrong or no longer valid.', 401);
    }
}
