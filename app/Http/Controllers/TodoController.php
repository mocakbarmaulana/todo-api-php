<?php

namespace App\Http\Controllers;

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
     * Get all todos controller
     *
     * @param TodoIndexRequest $todoIndexRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\Todo\TodoIndexRequest $todoIndexRequest)
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
            $response->todo,
            $response->statusCode
        );
    }
}
