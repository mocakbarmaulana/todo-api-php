<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoCreateRequest;
use App\Http\Requests\Todo\TodoIndexRequest;
use App\Service\TodoService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;

/**
 * @group Tod
 *
 * APIs for managing todos.
 * @header Accept application/json
 * @header Content-Type application/json
 * @package App\Http\Controllers
 */
class TodoController extends Controller
{
    use JsonResponseTrait;

    protected TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * Get all tod'os
     *
     * @param TodoIndexRequest $todoIndexRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TodoIndexRequest $todoIndexRequest): \Illuminate\Http\JsonResponse
    {
        $todoIndexDto = new \App\Dto\Todo\TodoIndexDto(
            userId: Auth::id(),
            isCompleted: $todoIndexRequest->get('is_completed', null),
            orderType: $todoIndexRequest->get('order_type', 'asc')
        );

        $response = $this->todoService->getAll($todoIndexDto);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }

    /**
     * Create a new tod'os
     *
     * @param TodoCreateRequest $todoCreateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TodoCreateRequest $todoCreateRequest): \Illuminate\Http\JsonResponse
    {
        $todoDto = new \App\Dto\Todo\TodoDto(
            id: null,
            title: $todoCreateRequest->get('title'),
            description: $todoCreateRequest->get('description'),
            completed: $todoCreateRequest->get('completed'),
            user_id: Auth::id(),
            completed_at: $todoCreateRequest->get('completed_at', null) ? now() : null,
            due_date: $todoCreateRequest->get('due_date', null),
            created_at: now(),
            updated_at: now(),
        );

        $response = $this->todoService->create($todoDto);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }
}
