<?php

use App\Service\RandomJokeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// Define mock data for a successful response
$mockJokeData = [
    'id' => 123,
    'type' => 'general',
    'setup' => 'Why did the chicken cross the road?',
    'punchline' => 'To get to the other side!',
];

it("test_get_joke_successfully", function() use ($mockJokeData) {
    // Mock Http Response
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => Http::response($mockJokeData, 200),
    ]);

    // Create an instance of RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Call getJoke method
    $response = $randomJokeService->getJoke();

    // Assert the response
    expect($response->status)->toBe('success');
    expect($response->message)->toBe('Joke retrieved successfully');
    expect($response->joke->id)->toBe($mockJokeData['id']);
    expect($response->joke->type)->toBe($mockJokeData['type']);
    expect($response->joke->setup)->toBe($mockJokeData['setup']);
    expect($response->joke->punchline)->toBe($mockJokeData['punchline']);
});

it("test_get_joke_404_response", function() {
    // Mock Http Response
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => Http::response(null, 404),
    ]);

    // Mock Log untuk mengecek apakah log error dipanggil
    Log::shouldReceive('error')
        ->once()
        ->with('Joke not found');

    // Create an instance of RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Call getJoke method
    $response = $randomJokeService->getJoke();

    // Assert the response
    expect($response->status)->toBe('error');
    expect($response->message)->toBe('Failed to get joke');
    expect($response->joke)->toBeNull();
});

it("test_get_joke_500_response", function() {
    // Mock Http Response
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => Http::response(null, 500),
    ]);

    // Mock Log untuk mengecek apakah log error dipanggil
    Log::shouldReceive('error')
        ->once()
        ->with('Server error while getting joke');

    // Create an instance of RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Call getJoke method
    $response = $randomJokeService->getJoke();

    // Assert the response
    expect($response->status)->toBe('error');
    expect($response->message)->toBe('Failed to get joke');
    expect($response->joke)->toBeNull();
});

it("test_get_joke_300_response", function() {
    // Mock Http Response
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => Http::response(null, 300),
    ]);

    // Mock Log untuk mengecek apakah log error dipanggil
    Log::shouldReceive('error')
        ->once()
        ->with('Failed to get joke');

    // Create an instance of RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Call getJoke method
    $response = $randomJokeService->getJoke();

    // Assert the response
    expect($response->status)->toBe('error');
    expect($response->message)->toBe('Failed to get joke');
    expect($response->joke)->toBeNull();
});

it("test_get_joke_network_error", function() {
    // Mock Http Response
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => function () {
            throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
        }
    ]);

    // Mock Log untuk mengecek apakah log error dipanggil
    Log::shouldReceive('error')
        ->once()
        ->with('Network error while getting joke: Connection timed out');

    // Create an instance of RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Call getJoke method
    $response = $randomJokeService->getJoke();

    // Assert the response
    expect($response->status)->toBe('error');
    expect($response->message)->toBe('Network error while getting joke');
    expect($response->joke)->toBeNull();
});

it("test_get_joke_unexpected_error", function() {
    // Buat mock Http Response dengan melemparkan Exception
    Http::fake([
        config('services.random_joke.base_uri') . '/random_joke' => function() {
            throw new \Exception('Unexpected server issue');
        },
    ]);

    // Mock Log untuk mengecek apakah log error dipanggil
    Log::shouldReceive('error')
        ->once()
        ->with('Unexpected error while getting joke: Unexpected server issue');

    // Buat instance dari RandomJokeService
    $client = Http::withOptions([])->baseUrl(config('services.random_joke.base_uri'));
    $randomJokeService = new RandomJokeService($client);

    // Panggil metode getJoke
    $response = $randomJokeService->getJoke();

    // Assert bahwa response memiliki status 'error' dan message yang diharapkan
    expect($response->status)->toBe('error');
    expect($response->message)->toBe('Unexpected error while getting joke');
    expect($response->joke)->toBeNull();
});
