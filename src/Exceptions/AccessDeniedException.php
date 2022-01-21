<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Your API token does not have permission to that resource.', 401);
    }
}
