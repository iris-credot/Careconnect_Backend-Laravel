<?php

namespace App\Exceptions;

class UnauthorizedError extends CustomError
{
    public function __construct($message = 'Unauthorized')
    {
        parent::__construct($message, 401);
    }
}
