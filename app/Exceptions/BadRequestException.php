<?php

namespace App\Exceptions;

class BadRequestError extends CustomError
{
    public function __construct($message = 'Bad Request')
    {
        parent::__construct($message, 400);
    }
}
