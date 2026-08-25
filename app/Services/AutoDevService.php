<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AutoDevService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.auto.dev';

    public function __construct()
    {
        $this->apiKey = config('services.autodev.key');
    }

    public function lookupByVin(string $vin): ?array
    {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/vin/{$vin}");

        if ($response->failed()) {
            return null;
        }

        return $this->mapToVehicleFields($response->json());
    }

    private function mapToVehicleFields(array $data): array
    {
        $transmission = match(strtolower($data['transmission'] ?? '')) {
            'automatic' => 'automatica',
            default     => 'manual',
        };

        $engineText = strtolower($data['engine'] ?? '');
        $fuelType = 'gasolina';
        if (str_contains($engineText, 'electric'))       $fuelType = 'electrico';
        elseif (str_contains($engineText, 'hybrid'))     $fuelType = 'hibrido';
        elseif (str_contains($engineText, 'diesel'))     $fuelType = 'diesel';

        return [
            'brand'             => $data['make'] ?? null,
            'model'             => $data['model'] ?? null,
            'model_alternative' => $data['trim'] ?? null,
            'transmission_type' => $transmission,
            'fuel_type'         => $fuelType,
        ];
    }
}
