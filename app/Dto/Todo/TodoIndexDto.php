<?php

namespace App\Dto\Todo;

use Spatie\LaravelData\Data;

class TodoIndexDto extends Data
{
    public function __construct(
        public string $userId,
        public string $orderType,
        public ?bool $isCompleted,
    ) {

        $this->orderType = $this->orderType ?? 'asc';
    }
}
