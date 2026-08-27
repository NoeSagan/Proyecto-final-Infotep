<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AutoScout24Service
{
    private string $apiKey;
    private string $host    = 'autoscout2411.p.rapidapi.com';
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

    /**
     * Obtiene listings paginados con filtros opcionales.
     * Solo cachea respuestas exitosas para no bloquear en caso de error/cuota.
     */
    public function getListings(array $params = []): ?array
    {
        $cacheKey = 'as24_listings_' . md5(serialize($params));

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/listings", $params);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        Cache::put($cacheKey, $data, 3600);

        return $data;
    }

    /**
     * Obtiene un listing individual por ID.
     */
    public function getListing(string $listingId): ?array
    {
        $cacheKey = "as24_listing_{$listingId}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/listings/{$listingId}");

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        Cache::put($cacheKey, $data, 86400);

        return $data;
    }

    public function getMakes(): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/taxonomy/makes");

        return $response->successful() ? $response->json('makes') : null;
    }

    public function getVehicleTypes(): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/taxonomy/vehicle-types");

        return $response->successful() ? $response->json() : null;
    }

    public function searchListings(string $make, string $model, int $year): ?array
    {
        $makeId = $this->resolveMakeId($make);
        if (! $makeId) {
            return null;
        }

        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/listings", ['makeId' => $makeId]);

        return $response->successful() ? $response->json('listings') : null;
    }

    /**
     * Busca la primera imagen disponible para una marca y modelo dados.
     * Primero intenta encontrar un listing que coincida con el modelo;
     * si no, devuelve la primera imagen de cualquier listing de esa marca.
     */
    public function getFirstImageByMakeModel(string $make, string $model): ?string
    {
        $makeId = $this->resolveMakeId($make);
        if (! $makeId) {
            return null;
        }

        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/listings", ['makeId' => $makeId]);

        if (! $response->successful()) {
            return null;
        }

        $listings = $response->json('listings', []);
        if (empty($listings)) {
            return null;
        }

        $modelLower = strtolower($model);
        $fallback   = null;

        foreach ($listings as $listing) {
            if (empty($listing['images'])) {
                continue;
            }

            $listingModel = strtolower($listing['vehicle']['model'] ?? '');

            // Guardar el primer resultado con imagen como fallback
            if ($fallback === null) {
                $fallback = $listing['images'][0];
            }

            // Coincidencia exacta o parcial con el modelo buscado
            if (
                $listingModel === $modelLower
                || str_contains($listingModel, $modelLower)
                || str_contains($modelLower, $listingModel)
            ) {
                return $listing['images'][0];
            }
        }

        return $fallback;
    }

    /**
     * Resuelve el ID de AutoScout24 para un nombre de marca.
     * El resultado se cachea 24 h para no llamar la API en cada request.
     */
    private function resolveMakeId(string $makeName): ?int
    {
        $makes = Cache::remember('autoscout24_makes', 86400, function () {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/taxonomy/makes");

            return $response->successful() ? ($response->json('makes') ?? []) : [];
        });

        $nameLower = strtolower(trim($makeName));

        // Coincidencia exacta
        foreach ($makes as $make) {
            if (strtolower($make['name']) === $nameLower) {
                return (int) $make['id'];
            }
        }

        // Coincidencia parcial (ej. "Mercedes-Benz" vs "Mercedes")
        foreach ($makes as $make) {
            $mLower = strtolower($make['name']);
            if (str_contains($mLower, $nameLower) || str_contains($nameLower, $mLower)) {
                return (int) $make['id'];
            }
        }

        return null;
    }
}
