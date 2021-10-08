<?php

namespace DeliciousBrains\SpinupWp\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('The specified resource could not be found.', 404);
    }
}
