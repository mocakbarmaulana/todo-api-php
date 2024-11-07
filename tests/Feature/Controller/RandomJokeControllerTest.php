<?php

use App\Dto\RandomJoke\RandomJokeResponseDto;

it("test_get_success", function() {
    $mockRandomJokeService = Mockery::mock(\App\Service\RandomJokeService::class);

    $this->app->instance(\App\Service\RandomJokeService::class, $mockRandomJokeService);
    $randomJokeController = $this->app->make(\App\Http\Controllers\RandomJokeController::class);

    // Setting expectation pada mock
    $mockRandomJokeService->shouldReceive('getJoke')
        ->once()
        ->andReturn(new RandomJokeResponseDto(
            joke: null,
            status: 'error',
            message: 'Failed to get joke'
        ));

    $response = $randomJokeController->get();

    expect($response->getStatusCode())->toBe(500);
    expect($response->getData()->status)->toBe('error');
    expect($response->getData()->message)->toBe('Failed to get joke');
    expect($response->getData()->data)->toBeNull();
});
