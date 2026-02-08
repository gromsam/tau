<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\StatsResource;
use App\Services\StatsService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        private readonly StatsService $statsService
    ) {}

    /**
     * Get task stats (total and by status).
     */
    public function index(Request $request): StatsResource
    {
        $stats = $this->statsService->getStats($request->user());

        return new StatsResource($stats);
    }
}
