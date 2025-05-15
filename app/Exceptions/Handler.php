<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\CustomError;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // CustomError handling (similar to Node.js structure)
        if ($exception instanceof CustomError) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->statusCode);
        }

        // Optionally handle Laravel's default exceptions more gracefully
        if ($exception instanceof AuthenticationException) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error.',
                'messages' => $exception->errors()
            ], 422);
        }

        // Default Laravel error handling
        return parent::render($request, $exception);
    }
}
