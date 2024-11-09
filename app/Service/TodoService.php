<?php

namespace App\Service;

use App\Constants\TodoConstant;
use App\Dto\Todo\TodoIndexDto;
use App\Dto\Todo\TodoResponseDto;
use App\Models\Todo as ModelTodo;
use App\Traits\JsonResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TodoService
{
    use JsonResponseTrait;

    protected ModelTodo $todo;

    public function __construct(ModelTodo $todo)
    {
        $this->todo = $todo;
    }

    /**
     * Get data by ID or throw ModelNotFoundException
     *
     * @param int $id
     * @return ModleTodo
     * @throws ModelNotFoundException
     */
    protected function findTodoOrFail(int $id): ModelTodo
    {
        return $this->todo->findOrFail($id);
    }

    /**
     * Get all data
     *
     * @param TodoIndexDto $todoIndexDto
     * @return TodoResponseDto
     */
    public function getAll(TodoIndexDto $todoIndexDto): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: [],
            status: 'error',
            message: 'Failed to get todo',
            statusCode: 500
        );

        try {
            $todos = $this->todo->query()->where('user_id', $todoIndexDto->userId)
                ->when($todoIndexDto->isCompleted !== null, function ($query) use ($todoIndexDto) {
                    return $query->where('completed', $todoIndexDto->isCompleted);
                })
                ->orderBy('created_at', $todoIndexDto->orderType)
                ->get();

            // If todos are found, update the response
            if ($todos->isNotEmpty()) {
                $response->todo = $todos;
                $response->status = 'success';
                $response->message = 'Success to get todo';
                $response->statusCode = 200;
            } else {
                $response->message = 'No todos found';
                $response->statusCode = 404;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database error while getting todo: {$e->getMessage()}");
            $response->message = 'Database error while getting todo';
        } catch (\Exception $e) {
            Log::error("Unexpected error while getting todo: {$e->getMessage()}");
            $response->message = 'Unexpected error while getting todo';
        }

        return $response;
    }


    /**
     * Create a data
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $data): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo->create($data);

        return $this->successResponse(TodoConstant::TODO_CREATE_SUCCESS, $todo, 201);
    }

    /**
     * Get data
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = $this->findTodoOrFail($id);

            return $this->successResponse(TodoConstant::TODO_GET_SUCCESS, $todo);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(TodoConstant::TODO_NOT_FOUND, [], 404);
        }
    }

    /**
     * Update a data
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = $this->findTodoOrFail($id);

            $todo->update($data);

            return $this->successResponse(TodoConstant::TODO_UPDATE_SUCCESS, $todo);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(TodoConstant::TODO_NOT_FOUND, [], 404);
        }
    }

    /**
     * Delete a data
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = $this->findTodoOrFail($id);

            $todo->delete();

            return $this->successResponse(TodoConstant::TODO_DELETE_SUCCESS, [], 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(TodoConstant::TODO_NOT_FOUND, [], 404);
        }
    }
}
