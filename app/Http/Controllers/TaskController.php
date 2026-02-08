<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    /**
     * List tasks for the authenticated user.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Task::class);

        $perPage = $request->integer('per_page', 15);
        $tasks = $this->taskService->getForUser($request->user(), $perPage);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a new task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->user(), $request->validated());

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing task.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $task = $this->taskService->update($task, $request->validated());

        return new TaskResource($task);
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return response()->json(null, 204);
    }
}
