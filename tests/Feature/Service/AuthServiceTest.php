<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

afterEach(function () {
    Mockery::close();
});

const USER_NAME = "Test User";
const USER_EMAIL = "test@example.com";
const USER_PASSWORD = "password";

it("logins returns a successful response", function () {
    $credentials = [
        "name" => USER_NAME,
        "email" => USER_EMAIL,
        "password" => USER_PASSWORD,
    ];

    $mockUser = Mockery::mock(User::class);
    $mockUser->shouldReceive("jsonSerialize")->andReturn([
        "name" => $credentials["name"],
        "email" => $credentials["email"],
    ]);

    // Act
    $loginRequest = new \App\Http\Requests\Auth\LoginRequest();
    $loginRequest->merge($credentials);

    // Mocking the Auth facade
    JWTAuth::shouldReceive("attempt")
        ->with($loginRequest->only(["email", "password"]))
        ->andReturn("token");
    Auth::shouldReceive("user")->andReturn($mockUser);

    $this->app->instance(User::class, $mockUser);
    $authService = $this->app->make(\App\Service\AuthService::class);
    $response = $authService->login($loginRequest);
    // $response = (new \App\Service\AuthService())->login($loginRequest);

    // Assert
    expect($response->getStatusCode())->toBe(200);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => "User logged in successfully",
        "user" => [
            "name" => $credentials["name"],
            "email" => $credentials["email"],
        ],
        "authorization" => [
            "token" => "token",
            "type" => "Bearer",
        ],
    ]);
});

it("logins returns an unauthorized response", function () {
    $credentials = [
        "email" => USER_EMAIL,
        "password" => "password",
    ];

    // Act
    $loginRequest = new \App\Http\Requests\Auth\LoginRequest();
    $loginRequest->merge($credentials);

    // Mocking the Auth facade
    JWTAuth::shouldReceive("attempt")
        ->with($loginRequest->only(["email", "password"]))
        ->andReturn(false);

    $authService = $this->app->make(\App\Service\AuthService::class);
    $response = $authService->login($loginRequest);
    // $response = (new \App\Service\AuthService())->login($loginRequest);

    // Assert
    expect($response->getStatusCode())->toBe(401);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "error",
        "message" => "Invalid credentials",
    ]);
});

it("register_sucessful", function () {
    $credentials = [
        "name" => USER_NAME,
        "email" => USER_EMAIL,
        "password" => USER_PASSWORD,
        "password_confirmation" => USER_PASSWORD,
    ];

    $mockUser = Mockery::mock(User::class);

    $this->app->instance(User::class, $mockUser);

    $mockUser->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) use ($credentials) {
            return $data['name'] === $credentials['name'] &&
                $data['email'] === $credentials['email'] &&
                Hash::check($credentials['password'], $data['password']);
        }))
        ->andReturn([
            "name" => $credentials["name"],
            "email" => $credentials["email"],
        ]);

    $registerRequest = new \App\Http\Requests\Auth\RegisterRequest();
    $registerRequest->merge($credentials);

    // Mocking the Auth facade
    JWTAuth::shouldReceive("attempt")
        ->with($registerRequest->only(["email", "password"]))
        ->andReturn(true);

    $authService = $this->app->make(\App\Service\AuthService::class);
    $response = $authService->register($registerRequest);

    expect($response->getStatusCode())->toBe(201);
    expect(json_decode(json_encode($response->getData()), true))->toBe([
        "status" => "success",
        "message" => "User registered successfully",
        "user" => [
            "name" => $credentials["name"],
            "email" => $credentials["email"],
        ],
        "authorization" => [
            "token" => true,
            "type" => "Bearer",
        ],
    ]);
});
