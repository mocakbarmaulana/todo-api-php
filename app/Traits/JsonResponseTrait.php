<?php

namespace App\Traits;

trait JsonResponseTrait
{
    /**
     * Send a success response
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(string $message, $data, int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    /**
     * Send an error response
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(string $message, $data, int $status = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    /**
     * Send a json response
     *
     * @param string $status
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse(string $status, string $message, $data, int $statusCode): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $statusCode);
    }
}
