<?php

use App\Dto\RandomJoke\RandomJokeDto;

it("test_valid_success", function () {
    $data = [
        'id' => 1,
        'type' => 'general',
        'setup' => 'What do you call a bear with no teeth?',
        'punchline' => 'A gummy bear'
    ];

    $resonse = new RandomJokeDto(
        id: $data['id'],
        type: $data['type'],
        setup: $data['setup'],
        punchline: $data['punchline']
    );

    expect($resonse->id)->toBe($data['id']);
    expect($resonse->type)->toBe($data['type']);
    expect($resonse->setup)->toBe($data['setup']);
    expect($resonse->punchline)->toBe($data['punchline']);
});
