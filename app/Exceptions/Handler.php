<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use PDOException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        // Exception handling for query database errors
        $this->renderable(function (QueryException $e) {
            return $this->jsonErrorResponse("Failed to create data due to a database error.", $e->getMessage(), 500);
        });

        // Exception handling for not found errors
        $this->renderable(function (ModelNotFoundException $e) {
            return $this->jsonErrorResponse("Data not found.", $e->getMessage(), 404);
        });

        // Exception handling for mass assignment errors
        $this->renderable(function (MassAssignmentException $e) {
            return $this->jsonErrorResponse("Failed to create data due to mass assignment issue.", $e->getMessage(), 422);
        });

        // Exception handling for database connection errors
        $this->renderable(function (PDOException $e) {
            return $this->jsonErrorResponse("Database connection error.", $e->getMessage(), 500);
        });

        // Exception handling for unexpected errors
        $this->renderable(function (Exception $e) {
            return $this->jsonErrorResponse("An unexpected error occurred.", $e->getMessage(), 500);
        });
    }

    /**
     * Create a standardized JSON error response.
     *
     * @param string $message
     * @param string $error
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonErrorResponse(string $message, string $error, int $statusCode): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "error" => $error,
        ], $statusCode);
    }
}
