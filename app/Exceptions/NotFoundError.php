<?php

namespace App\Exceptions;

class NotFoundError extends CustomError
{
    public function __construct($message = 'Resource Not Found')
    {
        parent::__construct($message, 404);
    }
}
