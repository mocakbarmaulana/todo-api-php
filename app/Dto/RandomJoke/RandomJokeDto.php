<?php

namespace App\Dto\RandomJoke;

use Spatie\LaravelData\Data;

class RandomJokeDto extends Data
{
    public function __construct(
        public int $id,
        public string $type,
        public string $setup,
        public string $punchline
    ) {}
}
