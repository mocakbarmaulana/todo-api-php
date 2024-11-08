<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Service\AuthService;

/**
 * @group Auth
 *
 * APIs for managing authentication
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login a user
     * @bodyparam email string required The email of the user. Example: test@example.com
     * @bodyparam password string required The password of the user. Example: password
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->authService->login($request);
    }

    /**
     * Register a user
     * @bodyparam name string required The name of the user. Example: John Doe
     * @bodyparam email string required The email of the user. Example: test@example.com
     * @bodyparam password string required The password of the user. Example: password
     * @bodyparam password_confirmation string required The password confirmation of the user. Example: password
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->authService->register($request);
    }

    /**
     * Logout a user
     * @authenticated
     * @header Accept application/json
     * @header Content-Type application/json
     * @header Authorization Bearer {token}
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        return $this->authService->logout();
    }

    /**
     * Get the authenticated user
     * @authenticated
     * @header Accept application/json
     * @header Content-Type application/json
     * @header Authorization Bearer {token}
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): \Illuminate\Http\JsonResponse
    {
        return $this->authService->me();
    }
}
