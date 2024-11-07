<?php

use App\Dto\RandomJoke\RandomJokeDto;
use App\Dto\RandomJoke\RandomJokeResponseDto;

it("test_valid_success", function () {
    $data = [
        'id' => 1,
        'type' => 'general',
        'setup' => 'What do you call a bear with no teeth?',
        'punchline' => 'A gummy bear'
    ];

    $resonse = new RandomJokeResponseDto(
        joke: new RandomJokeDto(
            id: $data['id'],
            type: $data['type'],
            setup: $data['setup'],
            punchline: $data['punchline']
        ),
        status: 'success',
        message: 'Joke retrieved successfully'
    );

    expect($resonse->joke->id)->toBe($data['id']);
    expect($resonse->joke->type)->toBe($data['type']);
    expect($resonse->joke->setup)->toBe($data['setup']);
    expect($resonse->joke->punchline)->toBe($data['punchline']);
    expect($resonse->status)->toBe('success');
    expect($resonse->message)->toBe('Joke retrieved successfully');
});

