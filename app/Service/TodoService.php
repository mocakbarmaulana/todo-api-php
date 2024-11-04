<?php

namespace App\Service;

use App\Constants\TodoConstant;
use App\Http\Requests\Todo\TodoCreateRequest;
use App\Http\Requests\Todo\TodoUpdateRequest;
use App\Models\Todo as ModelTodo;
use App\Traits\JsonResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    private function findTodoOrFail(int $id): ModelTodo
    {
        return $this->todo->findOrFail($id);
    }


    /**
     * Create a data
     *
     * @param TodoCreateRequest $todoCreateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TodoCreateRequest $todoCreateRequest): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo->create($todoCreateRequest->validated());

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
     * @param TodoUpdateRequest $todoUpdateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, TodoUpdateRequest $todoUpdateRequest): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = $this->findTodoOrFail($id);

            $todo->update($todoUpdateRequest->validated());

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

            return $this->successResponse(TodoConstant::TODO_DELETE_SUCCESS, $todo);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(TodoConstant::TODO_NOT_FOUND, [], 404);
        }
    }
}
