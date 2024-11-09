<?php

namespace App\Dto\Todo;

use App\Models\Todo as TodoModel;
use Spatie\LaravelData\Data;

class TodoResponseDto extends Data
{
    public function __construct(
        public ?TodoModel $todo,
        public string $status,
        public string $message,
        public string $statusCode
    ) {}
}
