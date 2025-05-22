<?php

namespace App\Exceptions;

class UnauthorizedException extends CustomError
{
    public function __construct($message = 'Unauthorized')
    {
        parent::__construct($message, 401);
    }
}
