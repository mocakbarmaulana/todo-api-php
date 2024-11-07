<?php

use App\Service\RandomJokeService;
use Illuminate\Support\Facades\Http;

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
        '*' => Http::response($mockJokeData, 200),
    ]);

    // Create an instance of RandomJokeService
    $client = Http::withOptions([]);
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
