<?php

it("valid_success_response", function () {
    $message = "Test Message";
    $data = ["test" => "data"];

    $controller = new class {
        use \App\Traits\JsonResponseTrait;
    };

    $response = $controller->successResponse($message, $data);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toBeObject()->toHaveProperty("status", "success");
    expect($response->getData())->toBeObject()->toHaveProperty("message", $message);
    expect($response->getData())->toBeObject()->toHaveProperty("data", (object) $data);
});

it("valid_error_response", function () {
    $message = "Test Message";
    $data = ["test" => "data"];

    $controller = new class {
        use \App\Traits\JsonResponseTrait;
    };

    $response = $controller->errorResponse($message, $data);

    expect($response->getStatusCode())->toBe(400);
    expect($response->getData())->toBeObject()->toHaveProperty("status", "error");
    expect($response->getData())->toBeObject()->toHaveProperty("message", $message);
    expect($response->getData())->toBeObject()->toHaveProperty("data", (object) $data);
});

