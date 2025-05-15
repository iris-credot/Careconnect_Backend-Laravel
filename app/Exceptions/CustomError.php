<?php

namespace App\Exceptions;

use Exception;

class CustomError extends Exception
{
    public $statusCode;

    public function __construct($message, $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}
