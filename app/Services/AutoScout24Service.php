<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AutoScout24Service
{
    private string $apiKey;
    private string $host = 'autoscout2411.p.rapidapi.com';
    private string $baseUrl = 'https://autoscout2411.p.rapidapi.com';

    public function __construct()
    {
        $this->apiKey = config('services.autoscout24.key');
    }

    private function headers(): array
    {
        return [
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->apiKey,
        ];
    }

    public function getVehicleTypes(): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/taxonomy/vehicle-types");

        return $response->successful() ? $response->json() : null;
    }

    public function getMakes(): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/taxonomy/makes");

        return $response->successful() ? $response->json() : null;
    }

    public function searchListings(string $make, string $model, int $year): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/listings", [
                'make'  => $make,
                'model' => $model,
                'year'  => $year,
            ]);

        return $response->successful() ? $response->json() : null;
    }
}
