<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CatFactService;
use Illuminate\Http\JsonResponse;

class CatFactController extends Controller
{
    public function __construct(
        private readonly CatFactService $catFactService
    ) {}

    /**
     * Get a random cat fact. Available after login.
     */
    public function index(): JsonResponse
    {
        $fact = $this->catFactService->getFact();

        if ($fact === null) {
            return response()->json(['message' => 'Unable to fetch cat fact'], 502);
        }

        return response()->json($fact);
    }
}
