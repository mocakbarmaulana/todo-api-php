<?php

namespace App\Dto\Todo;

use Spatie\LaravelData\Data;

class TodoDto extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public bool $completed
    ) {}
}
