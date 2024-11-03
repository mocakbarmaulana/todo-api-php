<?php

use App\Service\AuthService;

const USER_EMAIL = "test@example.com";
const USER_PASSWORD = "password";

it("login_controller_success", function() {
    $data = [
        "email" => USER_EMAIL,
        "password" => USER_PASSWORD,
    ];

    $mockAuthService = Mockery::mock(AuthService::class);

    $this->app->instance(AuthService::class, $mockAuthService);
    $authController = $this->app->make(\App\Http\Controllers\AuthController::class);

    $request = new \App\Http\Requests\Auth\LoginRequest($data);

    // Setting expectation pada mock
    $mockAuthService->shouldReceive('login')
        ->with($request)
        ->once()
        ->andReturn(new \Illuminate\Http\JsonResponse(['token' => 'mocked_token'], 200));

    $response = $authController->login($request);

    expect($response->getStatusCode())->toBe(200);
});

it("register_controller_success", function() {
    $data = [
        "name" => "Test User",
        "email" => USER_EMAIL,
        "password" => USER_PASSWORD,
    ];

    $mockAuthService = Mockery::mock(AuthService::class);

    $this->app->instance(AuthService::class, $mockAuthService);
    $authController = $this->app->make(\App\Http\Controllers\AuthController::class);

    $request = new \App\Http\Requests\Auth\RegisterRequest($data);

    // Setting expectation pada mock
    $mockAuthService->shouldReceive('register')
        ->with($request)
        ->once()
        ->andReturn(new \Illuminate\Http\JsonResponse(['token' => 'mocked_token'], 200));

    $response = $authController->register($request);

    expect($response->getStatusCode())->toBe(200);
});
