<?php

namespace App\Dto\Todo;

use App\Models\Todo as TodoModel;
use Spatie\LaravelData\Data;

class TodoResponseDto extends Data
{
     /**
     * @param TodoModel[]|null $todo
     */
    public function __construct(
        public ?array $todo,
        public string $status,
        public string $message,
        public string $statusCode
    ) {}
}
