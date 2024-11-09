<?php

it("test_invalid_todo_create_request", function () {
    $data = [
        "title" => "Test Title",
    ];

    $request = new \App\Http\Requests\Todo\TodoCreateRequest();

    $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});

it("test_valid_todo_create_request", function () {
    $data = [
        "title" => "Test Title",
        "description" => "Test Description",
        "completed" => false,
    ];

    $request = new \App\Http\Requests\Todo\TodoCreateRequest();

    $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it("authorizes_the_todo_create_request", function () {
    $request = new \App\Http\Requests\Todo\TodoCreateRequest();

    $isAuthorized = $request->authorize();

    expect($isAuthorized)->toBeTrue();
});
