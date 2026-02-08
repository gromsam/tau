<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly StatsService $statsService
    ) {}

    /**
     * @return Collection<int, Task>|LengthAwarePaginator
     */
    public function getForUser(User $user, int $perPage = 15): Collection|LengthAwarePaginator
    {
        return $this->taskRepository->getForUser($user, $perPage);
    }

    public function findById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    /**
     * @param  array{title: string, status: string}  $data
     */
    public function create(User $user, array $data): Task
    {
        $task = $this->taskRepository->create($user, $data);
        $this->statsService->clearCacheOnTaskChange($task);

        return $task;
    }

    /**
     * @param  array{title?: string, status?: string}  $data
     */
    public function update(Task $task, array $data): Task
    {
        $task = $this->taskRepository->update($task, $data);
        $this->statsService->clearCacheOnTaskChange($task);

        return $task;
    }

    public function delete(Task $task): bool
    {
        $this->statsService->clearCacheOnTaskChange($task);

        return $this->taskRepository->delete($task);
    }
}
