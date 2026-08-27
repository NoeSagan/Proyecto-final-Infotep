<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CarSpecsService
{
    private string $apiKey;
    private string $host    = 'car-specs.p.rapidapi.com';
    private string $baseUrl = 'https://car-specs.p.rapidapi.com/v2/cars';

    public function __construct()
    {
        $this->apiKey = config('services.car_specs.key');
    }

    private function headers(): array
    {
        return [
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->apiKey,
        ];
    }

    public function getMakes(): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/makes");

        return $response->successful() ? $response->json() : null;
    }

    public function getModelsByMake(string $makeId): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/makes/{$makeId}/models");

        return $response->successful() ? $response->json() : null;
    }

    public function getModelDetails(string $modelId): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/models/{$modelId}");

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Obtiene los modelos de varias marcas en paralelo.
     * Retorna array indexado por makeId => [models].
     * Solo cachea por marca los resultados exitosos.
     */
    public function getModelsInParallel(array $makes): array
    {
        $uncached = [];
        $result   = [];

        foreach ($makes as $make) {
            $key = "carspecs_models_{$make['id']}";
            $cached = Cache::get($key);
            if ($cached !== null) {
                $result[$make['id']] = $cached;
            } else {
                $uncached[] = $make;
            }
        }

        if (empty($uncached)) {
            return $result;
        }

        $headers = $this->headers();
        $baseUrl = $this->baseUrl;

        $responses = Http::pool(function ($pool) use ($uncached, $headers, $baseUrl) {
            foreach ($uncached as $make) {
                $pool->as((string) $make['id'])
                     ->withHeaders($headers)
                     ->get("{$baseUrl}/makes/{$make['id']}/models");
            }
        });

        foreach ($uncached as $make) {
            $response = $responses[(string) $make['id']] ?? null;
            if ($response && ! ($response instanceof \Throwable) && $response->successful()) {
                $models = $response->json() ?? [];
                Cache::put("carspecs_models_{$make['id']}", $models, 86400);
                $result[$make['id']] = $models;
            }
        }

        return $result;
    }
}
