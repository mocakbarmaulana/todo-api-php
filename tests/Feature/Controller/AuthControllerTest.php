<?php

use App\Service\AuthService;

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

it("logout_controller_success", function() {
    $mockAuthService = Mockery::mock(AuthService::class);

    $this->app->instance(AuthService::class, $mockAuthService);
    $authController = $this->app->make(\App\Http\Controllers\AuthController::class);

    // Setting expectation pada mock
    $mockAuthService->shouldReceive('logout')
        ->once()
        ->andReturn(new \Illuminate\Http\JsonResponse(['message' => 'User logged out'], 200));

    $response = $authController->logout();

    expect($response->getStatusCode())->toBe(200);
});

it("me_controller_success", function() {
    $mockAuthService = Mockery::mock(AuthService::class);

    $this->app->instance(AuthService::class, $mockAuthService);
    $authController = $this->app->make(\App\Http\Controllers\AuthController::class);

    // Setting expectation pada mock
    $mockAuthService->shouldReceive('me')
        ->once()
        ->andReturn(new \Illuminate\Http\JsonResponse(['user' => 'mocked_user'], 200));

    $response = $authController->me();

    expect($response->getStatusCode())->toBe(200);
});
