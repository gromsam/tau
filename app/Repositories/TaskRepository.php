<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    /**
     * Get tasks for user. Admin gets all tasks.
     *
     * @return Collection<int, Task>|LengthAwarePaginator
     */
    public function getForUser(User $user, int $perPage = 15): Collection|LengthAwarePaginator
    {
        $query = $user->is_admin
            ? Task::query()->with('user')
            : Task::query()->where('user_id', $user->id);

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function findById(int $id): ?Task
    {
        return Task::query()->find($id);
    }

    /**
     * @param  array{title: string, status: string}  $data
     */
    public function create(User $user, array $data): Task
    {
        return Task::query()->create([
            ...$data,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @param  array{title?: string, status?: string}  $data
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Get stats (total and by status) for user. Admin gets all stats.
     *
     * @return array{total: int, by_status: array<string, int>}
     */
    public function getStatsForUser(User $user): array
    {
        $query = $user->is_admin
            ? Task::query()
            : Task::query()->where('user_id', $user->id);

        $byStatus = $query
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $byStatus = array_map('intval', $byStatus);
        $total = array_sum($byStatus);

        return [
            'total' => $total,
            'by_status' => $byStatus,
        ];
    }
}
