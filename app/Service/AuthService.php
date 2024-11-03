<?php

namespace App\Service;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{

    protected User $user;

    /**
     * AuthService constructor.
     * @param User $user
     */

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Login user
     *
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $loginRequest): \Illuminate\Http\JsonResponse
    {
        $credentials = $loginRequest->only(["email", "password"]);

        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid credentials",
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            "status" => "success",
            "message" => "User logged in successfully",
            "user" => $user,
            "authorization" => [
                "token" => $token,
                "type" => "Bearer",
            ]
        ], 200);
    }

    /**
     * Register user
     *
     * @param RegisterRequest $registerRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $registerRequest): \Illuminate\Http\JsonResponse
    {
        $user = $this->user->create([
            "name" => $registerRequest->name,
            "email" => $registerRequest->email,
            "password" => Hash::make($registerRequest->password),
        ]);

        $token = JWTAuth::attempt([
            "email" => $registerRequest->email,
            "password" => $registerRequest->password,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "User registered successfully",
            "user" => $user,
            "authorization" => [
                "token" => $token,
                "type" => "Bearer",
            ]
        ], 201);
    }
}
