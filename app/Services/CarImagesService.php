<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CarImagesService
{
    private string $apiKey;
    private string $baseUrl = 'https://www.carimagesapi.com/api';

    public function __construct()
    {
        $this->apiKey = config('services.car_images.key');
    }

    public function getImages(string $make, string $model, int $year): ?array
    {
        $response = Http::get("{$this->baseUrl}/images", [
            'api_key' => $this->apiKey,
            'make'    => $make,
            'model'   => $model,
            'year'    => $year,
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        return $data['images'] ?? $data ?? null;
    }

    public function getFirstImage(string $make, string $model, int $year): ?string
    {
        $images = $this->getImages($make, $model, $year);

        if (empty($images)) {
            return null;
        }

        $first = is_array($images[0]) ? ($images[0]['url'] ?? null) : $images[0];

        return $first;
    }
}
