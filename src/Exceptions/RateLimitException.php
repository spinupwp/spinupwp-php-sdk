<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class RateLimitException extends Exception
{
    public function __construct()
    {
        parent::__construct("You've hit the rate limit on API requests.", 429);
    }
}