<?php

namespace App\Service;

use App\Constants\TodoConstant;
use App\Dto\Todo\TodoDto;
use App\Dto\Todo\TodoIndexDto;
use App\Dto\Todo\TodoResponseDto;
use App\Models\Todo as TodoModel;
use App\Traits\JsonResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TodoService
{
    use JsonResponseTrait;

    protected TodoModel $todo;

    public function __construct(TodoModel $todo)
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
    protected function findTodoOrFail(int $id): TodoModel
    {
        return $this->todo->findOrFail($id);
    }

    /**
     * Get all data
     *
     * @param TodoIndexDto $todoIndexDto
     * @return TodoResponseDto
     */
    public function get(TodoIndexDto $todoIndexDto): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: null,
            status: 'error',
            message: sprintf(TodoConstant::TODO_FAILED, 'get'),
            statusCode: 500
        );

        try {
            $todos = $this->todo->query()->where('user_id', $todoIndexDto->userId)
                ->when($todoIndexDto->isCompleted !== null, function ($query) use ($todoIndexDto) {
                    return $query->where('completed', $todoIndexDto->isCompleted);
                })
                ->orderBy('created_at', $todoIndexDto->orderType)
                ->get();

            $response->status = 'success';
            // If todos are found, update the response
            if ($todos->isNotEmpty()) {
                $response->todo = $todos;
                $response->message = sprintf(TodoConstant::TODO_SUCCESSFULLY, 'get');
                $response->statusCode = 200;
            } else {
                $response->message = 'No todos found';
                $response->statusCode = 200;
            }
        } catch (\Exception $e) {
            Log::error("Unexpected error while getting todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'getting');
        }

        return $response;
    }


    /**
     * Create a data
     *
     * @param TodoDto $data
     * @return TodoResponseDto
     */
    public function create(TodoDto $data): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: null,
            status: 'error',
            message: sprintf(TodoConstant::TODO_FAILED, 'create'),
            statusCode: 500
        );

        try {
            $todo = $this->todo->create($data->toArray());

            $response->status = 'success';
            $response->todo = $todo;
            $response->message = sprintf(TodoConstant::TODO_SUCCESSFULLY, 'created');
            $response->statusCode = 201;
        } catch (\Exception $e) {
            Log::error("Unexpected error while creating todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'creating');
        }

        return $response;
    }

    /**
     * Get data
     *
     * @param int $id
     * @return TodoResponseDto
     */
    public function detail(int $id): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: null,
            status: 'error',
            message: sprintf(TodoConstant::TODO_FAILED, 'get'),
            statusCode: 500
        );


        try {
            $todo = $this->findTodoOrFail($id);

            $response->status = 'success';
            $response->todo = $todo;
            $response->message = sprintf(TodoConstant::TODO_SUCCESSFULLY, 'get');
            $response->statusCode = 200;
        } catch (ModelNotFoundException $e) {
            Log::error("Todo not found: {$e->getMessage()}");
            $response->message = TodoConstant::TODO_NOT_FOUND;
            $response->statusCode = 404;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database error while getting todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Database', 'getting');
        } catch (\Exception $e) {
            Log::error("Unexpected error while getting todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'getting');
        }

        return $response;
    }

    /**
     * Update a data
     *
     * @param int $id
     * @param TodoDto $data
     * @return TodoResponseDto
     */
    public function update(int $id, TodoDto $data): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: null,
            status: 'error',
            message: sprintf(TodoConstant::TODO_FAILED, 'update'),
            statusCode: 500
        );

        try {
            $todo = $this->findTodoOrFail($id);
            $data->created_at = $todo->created_at;

            $todo->update($data->toArray());

            $response->status = 'success';
            $response->todo = $todo;
            $response->message = sprintf(TodoConstant::TODO_SUCCESSFULLY, 'update');
            $response->statusCode = 200;
        } catch (ModelNotFoundException $e) {
            Log::error("Todo not found: {$e->getMessage()}");
            $response->message = TodoConstant::TODO_NOT_FOUND;
            $response->statusCode = 404;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database error while updating todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Database', 'updating');
        } catch (\Exception $e) {
            Log::error("Unexpected error while updating todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'updating');
        }

        return $response;
    }

    /**
     * Delete a data
     *
     * @param int $id
     * @return TodoResponseDto
     */
    public function delete(int $id): TodoResponseDto
    {
        $response = new TodoResponseDto(
            todo: null,
            status: 'error',
            message: sprintf(TodoConstant::TODO_FAILED, 'delete'),
            statusCode: 500
        );
            
        try {
            $todo = $this->findTodoOrFail($id);

            $todo->delete();

            $response->status = 'success';
            $response->message = sprintf(TodoConstant::TODO_SUCCESSFULLY, 'deleted');
            $response->statusCode = 200;
        } catch (ModelNotFoundException $e) {
            Log::error("Todo not found: {$e->getMessage()}");
            $response->message = TodoConstant::TODO_NOT_FOUND;
            $response->statusCode = 404;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database error while deleting todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Database', 'deleting');
        } catch (\Exception $e) {
            Log::error("Unexpected error while deleting todo: {$e->getMessage()}");
            $response->message = sprintf(TodoConstant::TODO_EXCEPTION, 'Unexpected', 'deleting');
        }

        return $response;
    }
}
