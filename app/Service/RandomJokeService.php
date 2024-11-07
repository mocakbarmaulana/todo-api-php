<?php

namespace App\Service;

use App\Dto\RandomJoke\RandomJokeDto;
use App\Dto\RandomJoke\RandomJokeResponseDto;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class RandomJokeService
{
    protected PendingRequest $client;

    public function __construct(PendingRequest $client)
    {
        $this->client = $client;
    }

    public function getJoke(): RandomJokeResponseDto
    {
        // Set default response in case of failure
        $jokeResponse = new RandomJokeResponseDto(
            joke: null,
            status: 'error',
            message: 'Failed to get joke'
        );

        try {
            $response = $this->client->get('/random_joke');

            if ($response->successful() && $data = $response->json()) {
                $jokeResponse->joke = new RandomJokeDto(
                    id: $data['id'],
                    type: $data['type'],
                    setup: $data['setup'],
                    punchline: $data['punchline']
                );
                $jokeResponse->status = 'success';
                $jokeResponse->message = 'Joke retrieved successfully';
            } else {
                $this->logErrorResponse($response, 'Failed to get joke');
            }
        } catch (ConnectionException $e) {
            Log::error("Network error while getting joke: {$e->getMessage()}");
            $jokeResponse->message = 'Network error while getting joke';
        } catch (RequestException $e) {
            Log::error("Request error while getting joke: {$e->getMessage()}");
            $jokeResponse->message = 'Request error while getting joke';
        } catch (\Exception $e) {
            Log::error("Unexpected error while getting joke: {$e->getMessage()}");
            $jokeResponse->message = 'Unexpected error while getting joke';
        }

        return $jokeResponse;
    }

    /**
     * Log and handle unsuccessful response
     *
     * @param \Illuminate\Http\Client\Response $response
     * @param string $defaultMessage
     * @return void
     */
    protected function logErrorResponse(Response $response, string $defaultMessage): void
    {
        $status = $response->status();
        $message = match (true) {
            $status === 404 => 'Joke not found',
            $status >= 500 => 'Server error while getting joke',
            default => $defaultMessage,
        };

        Log::error($message);
    }
}
