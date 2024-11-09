<?php

it("test_valid_success", function() {
    $userId = '1';
    $orderType = 'desc';
    $isCompleted = true;

    $dto = new \App\Dto\Todo\TodoIndexDto($userId, $orderType, $isCompleted);

    expect($dto->userId)->toBe($userId);
    expect($dto->orderType)->toBe($orderType);
    expect($dto->isCompleted)->toBe($isCompleted);
});
