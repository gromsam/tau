<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Cache;

class StatsService
{
    private const CACHE_TTL_SECONDS = 60;

    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {}

    /**
     * Get stats for user (total and by status). Cached 60 seconds.
     *
     * @return array{total: int, by_status: array<string, int>}
     */
    public function getStats(User $user): array
    {
        $cacheKey = $this->cacheKey($user);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($user): array {
            return $this->taskRepository->getStatsForUser($user);
        });
    }

    /**
     * Clear stats cache when a task changes.
     */
    public function clearCacheOnTaskChange(Task $task): void
    {
        Cache::forget($this->cacheKeyForUser($task->user_id));
        Cache::forget('stats.all');
    }

    private function cacheKey(User $user): string
    {
        return $user->is_admin ? 'stats.all' : $this->cacheKeyForUser($user->id);
    }

    private function cacheKeyForUser(int $userId): string
    {
        return "stats.user.{$userId}";
    }
}
