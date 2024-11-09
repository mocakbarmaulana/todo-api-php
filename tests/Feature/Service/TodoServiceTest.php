<?php

use App\Constants\TodoConstant;
use App\Dto\Todo\TodoIndexDto;
use Faker\Factory;
use Illuminate\Support\Facades\Log;

describe("test_get_services", function () {
    it("test_get_services_success", function () {
        $userId = 1;
        $isCompleted = true;
        $orderType = 'asc';

        // Mock data untuk response
        $mockTodos = collect([
            new \App\Models\Todo([
                "title" => "Sample Todo 1",
                "description" => "This is a sample todo 1",
                "completed" => true,
                "user_id" => $userId
            ]),
            new \App\Models\Todo([
                "title" => "Sample Todo 2",
                "description" => "This is a sample todo 2",
                "completed" => true,
                "user_id" => $userId
            ]),
        ]);

        $mockTodo = Mockery::mock(\App\Models\Todo::class);
        $mockTodo->shouldReceive('query->where->when->orderBy->get')->andReturn($mockTodos);

        $this->app->instance(\App\Models\Todo::class, $mockTodo);

        // Membuat instance TodoService
        $todoService = $this->app->make(\App\Service\TodoService::class);

        // Membuat TodoIndexDto untuk parameter method `get`
        $todoIndexDto = new \App\Dto\Todo\TodoIndexDto(
            userId: $userId,
            isCompleted: $isCompleted,
            orderType: $orderType
        );

        // Memanggil method `get`
        $response = $todoService->get($todoIndexDto);

        // Assertions
        expect($response->statusCode)->toBe(200);
        expect($response->status)->toBe("success");
        expect($response->message)->toBe(sprintf(TodoConstant::TODO_SUCCESSFULLY, 'get'));
        expect($response->todo)->toBe($mockTodos);
    });

    it("test_get_services_query_exception", function () {
        $userId = 1;
        $isCompleted = true;
        $orderType = 'asc';

        $mockTodo = Mockery::mock(\App\Models\Todo::class);
        $mockTodo->shouldReceive('query->where->when->orderBy->get')
            ->andThrow(new \Exception("Database error"));

        $this->app->instance(\App\Models\Todo::class, $mockTodo);

        // Membuat instance TodoService
        $todoService = $this->app->make(\App\Service\TodoService::class);

        // Membuat TodoIndexDto untuk parameter method `get`
        $todoIndexDto = new \App\Dto\Todo\TodoIndexDto(
            userId: $userId,
            isCompleted: $isCompleted,
            orderType: $orderType
        );

        // Log assertion
        Log::shouldReceive("error")->once()
            ->with("Unexpected error while getting todo: Database error");

        // Memanggil method `get`
        $response = $todoService->get($todoIndexDto);


        // Assertions untuk kondisi QueryException
        expect($response->statusCode)->toBe(500);
        expect($response->status)->toBe("error");
        expect($response->message)->toBe(sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'getting'));
    });
});

describe("test_create_services", function () {
    it("test_create_services_success", function () {
        $data = [
            "title" => Factory::create()->sentence,
            "description" => Factory::create()->words(30, true),
            "completed" => Factory::create()->boolean,
            "user_id" => Factory::create()->numberBetween(1, 100),
            "created_at" => now(),
            "updated_at" => now(),
            "completed_at" => now(),
            "due_date" => now(),
            "id" => Factory::create()->numberBetween(1, 100),
        ];

        $todoDto = new \App\Dto\Todo\TodoDto(
            title: $data["title"],
            description: $data["description"],
            completed: $data["completed"],
            user_id: $data["user_id"],
            created_at: $data["created_at"],
            updated_at: $data["updated_at"],
            completed_at: $data["completed_at"],
            due_date: $data["due_date"],
            id: $data["id"],
        );

        $mockTodo = Mockery::mock(\App\Models\Todo::class);
        $mockTodo->shouldReceive("create")->andReturn(new \App\Models\Todo($data));

        $this->app->instance(\App\Models\Todo::class, $mockTodo);

        $todoService = $this->app->make(\App\Service\TodoService::class);
        $response = $todoService->create($todoDto);

        expect($response->statusCode)->toBe(201);
        expect($response->status)->toBe("success");
        expect($response->message)->toBe(sprintf(TodoConstant::TODO_SUCCESSFULLY, 'created'));
    });

    it("test_create_services_exception", function () {
        $data = [
            "title" => Factory::create()->sentence,
            "description" => Factory::create()->words(30, true),
            "completed" => Factory::create()->boolean,
            "user_id" => Factory::create()->numberBetween(1, 100),
            "created_at" => now(),
            "updated_at" => now(),
            "completed_at" => now(),
            "due_date" => now(),
            "id" => Factory::create()->numberBetween(1, 100),
        ];

        $todoDto = new \App\Dto\Todo\TodoDto(
            title: $data["title"],
            description: $data["description"],
            completed: $data["completed"],
            user_id: $data["user_id"],
            created_at: $data["created_at"],
            updated_at: $data["updated_at"],
            completed_at: $data["completed_at"],
            due_date: $data["due_date"],
            id: $data["id"],
        );

        $mockTodo = Mockery::mock(\App\Models\Todo::class);
        $mockTodo->shouldReceive("create")->andThrow(new \Exception("Database error"));

        $this->app->instance(\App\Models\Todo::class, $mockTodo);

        Log::shouldReceive("error")->once()
            ->with("Unexpected error while creating todo: Database error");

        $todoService = $this->app->make(\App\Service\TodoService::class);
        $response = $todoService->create($todoDto);

        expect($response->statusCode)->toBe(500);
        expect($response->status)->toBe("error");
        expect($response->message)->toBe(sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'creating'));
    });
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
