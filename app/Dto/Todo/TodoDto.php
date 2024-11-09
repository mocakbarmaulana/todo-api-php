<?php

namespace App\Dto\Todo;

use Spatie\LaravelData\Data;

class TodoDto extends Data
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public bool $completed,
        public string $user_id,
        public ?string $completed_at,
        public ?string $due_date,
        public ?string $created_at,
        public ?string $updated_at,
    ) {
    }
}
