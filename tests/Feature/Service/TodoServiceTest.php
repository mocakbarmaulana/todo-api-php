<?php

use App\Constants\TodoConstant;
use Faker\Factory;

it("test_create_todo_success", function () {
    $data = [
        "title" => Factory::create()->sentence,
        "description" => Factory::create()->words(30, true),
    ];

    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("create")->andReturn(new \App\Models\Todo($data));

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->create($data);

    expect($response->getStatusCode())->toBe(201);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => TodoConstant::TODO_CREATE_SUCCESS,
        "data" => $data,
    ]);
});

it("test_get_todo_success", function () {
    $data = [
        "title" => Factory::create()->sentence,
        "description" => Factory::create()->word,
    ];
    
    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andReturn(new \App\Models\Todo($data));

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->get(1);

    expect($response->getStatusCode())->toBe(200);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => TodoConstant::TODO_GET_SUCCESS,
        "data" => $data,
    ]);
});

it("test_get_todo_fail", function () {
    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->get(1);

    expect($response->getStatusCode())->toBe(404);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "error",
        "message" => "Todo not found",
        "data" => [],
    ]);
});

it("test_update_todo_success", function () {
    $data = [
        "title" => Factory::create()->sentence,
        "description" => Factory::create()->words,
    ];

    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andReturn(new \App\Models\Todo($data));
    $mockTodo->shouldReceive("update")->andReturn(new \App\Models\Todo($data));

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->update(1, $data);

    expect($response->getStatusCode())->toBe(200);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => TodoConstant::TODO_UPDATE_SUCCESS,
        "data" => $data,
    ]);
});

it("test_update_todo_fail", function () {
    $data = [
        "title" => Factory::create()->sentence,
        "description" => Factory::create()->words,
    ];

    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->update(1, $data);

    expect($response->getStatusCode())->toBe(404);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "error",
        "message" => "Todo not found",
        "data" => [],
    ]);
});

it("test_delete_todo_success", function () {
    $data = [
        "title" => Factory::create()->sentence,
        "description" => Factory::create()->words,
    ];

    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andReturn(new \App\Models\Todo($data));
    $mockTodo->shouldReceive("delete")->andReturn(true);

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->delete(1);

    expect($response->getStatusCode())->toBe(200);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => TodoConstant::TODO_DELETE_SUCCESS,
        "data" => [],
    ]);
});

it("test_delete_todo_fail", function () {
    $mockTodo = Mockery::mock(\App\Models\Todo::class);
    $mockTodo->shouldReceive("findOrFail")->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());

    $this->app->instance(\App\Models\Todo::class, $mockTodo);

    $todoService = $this->app->make(\App\Service\TodoService::class);
    $response = $todoService->delete(1);

    expect($response->getStatusCode())->toBe(404);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "error",
        "message" => "Todo not found",
        "data" => [],
    ]);
});
