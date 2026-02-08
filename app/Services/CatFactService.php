<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CatFactService
{
    private const API_URL = 'https://catfact.ninja/fact';

    private const CACHE_TTL_SECONDS = 300; // 5 minutes

    private const CACHE_KEY = 'cat_fact';

    /**
     * Get a random cat fact. Cached 5 minutes.
     *
     * @return array{fact: string, length: int}|null
     */
    public function getFact(): ?array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function (): ?array {
            $response = Http::timeout(5)->get(self::API_URL);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();
            if (! is_array($data) || ! isset($data['fact'])) {
                return null;
            }

            return [
                'fact' => (string) $data['fact'],
                'length' => (int) ($data['length'] ?? 0),
            ];
        });
    }
}
