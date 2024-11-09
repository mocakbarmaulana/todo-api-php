<?php

it("test_valid_success", function () {
    $data = [
        'id' => 1,
        'title' => 'type',
        'description' => 'description',
        'completed' => false,
        'user_id' => 'user_id',
        'completed_at' => 'completed_at',
        'due_date' => 'due_date',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    $resonse = new \App\Dto\Todo\TodoDto(
        $data['id'],
        $data['title'],
        $data['description'],
        $data['completed'],
        $data['user_id'],
        $data['completed_at'],
        $data['due_date'],
        $data['created_at'],
        $data['updated_at'],
    );

    expect($resonse->id)->toBe($data['id']);
    expect($resonse->title)->toBe($data['title']);
    expect($resonse->description)->toBe($data['description']);
    expect($resonse->completed)->toBe($data['completed']);
    expect($resonse->user_id)->toBe($data['user_id']);
    expect($resonse->completed_at)->toBe($data['completed_at']);
    expect($resonse->due_date)->toBe($data['due_date']);
    expect($resonse->created_at)->toBe($data['created_at']);
    expect($resonse->updated_at)->toBe($data['updated_at']);
});