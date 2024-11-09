<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoCreateRequest;
use App\Http\Requests\Todo\TodoIndexRequest;
use App\Http\Requests\Todo\TodoUpdateRequest;
use App\Service\TodoService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;

/**
 * @group Tod'os
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
     * @queryParam is_completed boolean Filter by completed status. Example: 1
     * @queryParam order_type string Order by created_at. Example: desc
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

        $response = $this->todoService->get($todoIndexDto);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }

    /**
     * Create a new tod'os
     * @bodyParam title string required The title of the tod'os. Example: My first tod'os
     * @bodyParam description string required The description of the tod'os. Example: This is my first tod'os
     * @bodyParam completed boolean required The completed status of the tod'os. Example: 0
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
            completed_at: $todoCreateRequest->get('completed', null) ? now() : null,
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

    /**
     * Show a tod'os
     *
     * @urlParam id required The ID of the tod'os. Example: 1
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $response = $this->todoService->detail($id);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }

    /**
     * Update a tod'os
     * @urlParam id required The ID of the tod'os. Example: 1
     * @bodyParam title string required The title of the tod'os. Example: My first tod'os
     * @bodyParam description string required The description of the tod'os. Example: This is my first tod'os
     * @bodyParam completed boolean required The completed status of the tod'os. Example: 0
     *
     * @param TodoUpdateRequest $todoUpdateRequest
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TodoUpdateRequest $todoUpdateRequest, int $id): \Illuminate\Http\JsonResponse
    {
        $todoDto = new \App\Dto\Todo\TodoDto(
            id: $id,
            title: $todoUpdateRequest->get('title'),
            description: $todoUpdateRequest->get('description'),
            completed: $todoUpdateRequest->get('completed'),
            user_id: Auth::id(),
            completed_at: $todoUpdateRequest->get('completed', null) ? now() : null,
            due_date: $todoUpdateRequest->get('due_date', null),
            created_at: null,
            updated_at: now(),
        );

        $response = $this->todoService->update($id, $todoDto);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }

    /**
     * Delete a tod'os
     *
     * @urlParam id required The ID of the tod'os. Example: 1
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): \Illuminate\Http\JsonResponse
    {
        $response = $this->todoService->delete($id);

        return $this->jsonResponse(
            $response->status,
            $response->message,
            $response->todo ?? [],
            $response->statusCode
        );
    }
}
