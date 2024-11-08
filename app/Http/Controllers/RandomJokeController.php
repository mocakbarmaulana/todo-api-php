<?php

namespace App\Http\Controllers;

use App\Service\RandomJokeService;

/**
 * @group Random Joke
 * APIs for getting a random joke
 * @authenticated
 * @header Accept application/json
 * @header Content-Type application/json
 * @header Authorization Bearer {token}
 * @package App\Http\Controllers
 */
class RandomJokeController extends Controller
{
    protected RandomJokeService $randomJokeService;

    public function __construct(RandomJokeService $randomJokeService)
    {
        $this->randomJokeService = $randomJokeService;
    }

    /**
     * Get a random joke
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(): \Illuminate\Http\JsonResponse
    {
        $response = $this->randomJokeService->getJoke();

        return response()->json([
            'status' => $response->status,
            'message' => $response->message,
            'data' => $response->joke?->toArray()
        ], $response->status === 'success' ? 200 : 500);
    }
}
