<?php

namespace App\Dto\Todo;

use App\Models\Todo as TodoModel;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class TodoResponseDto extends Data
{
    public function __construct(
        public TodoModel|Collection|null $todo,
        public string $status,
        public string $message,
        public string $statusCode
    ) {}
}
