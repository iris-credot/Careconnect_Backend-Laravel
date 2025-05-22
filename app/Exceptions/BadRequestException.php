<?php

namespace App\Exceptions;

class BadRequestException extends CustomError
{
    public function __construct($message = 'Bad Request')
    {
        parent::__construct($message, 400);
    }
}
