<?php

namespace App\Dto\RandomJoke;

use Spatie\LaravelData\Data;

class RandomJokeResponseDto extends Data
{
    public function __construct(
        public RandomJokeDto|null $joke,
        public string $status,
        public string $message
    ) {}
}
