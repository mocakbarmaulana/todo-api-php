<?php

it("test_invalid_todo_index_request", function () {
    $data = [
        "is_completed" => "invalid",
        "order_type" => "invalid",
    ];

    $request = new \App\Http\Requests\Todo\TodoIndexRequest();

    $validator = \Illuminate\Support\Facades\Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});

it("test_authorizes_the_todo_index_request", function () {
    $request = new \App\Http\Requests\Todo\TodoIndexRequest();

    $isAuthorized = $request->authorize();

    expect($isAuthorized)->toBeTrue();
});
