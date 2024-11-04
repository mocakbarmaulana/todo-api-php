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
}
