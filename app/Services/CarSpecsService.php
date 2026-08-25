<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CarSpecsService
{
    private string $apiKey;
    private string $host = 'car-specs.p.rapidapi.com';
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
}
