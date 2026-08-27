<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CarImagesService
{
    public function getBrandLogo(string $make): ?string
    {
        $cacheKey = 'brand_logo_' . md5(strtolower($make));

        return Cache::remember($cacheKey, 604800, function () use ($make) {
            return $this->fetchBrandLogo($make);
        });
    }

    private function fetchBrandLogo(string $make): ?string
    {
        $response = Http::timeout(8)
            ->withoutVerifying()
            ->withHeaders(['User-Agent' => 'AutoAlquiler/1.0 (noeliapichardo0624@gmail.com)'])
            ->get('https://commons.wikimedia.org/w/api.php', [
                'action'       => 'query',
                'generator'    => 'search',
                'gsrsearch'    => "{$make} logo",
                'gsrnamespace' => 6,
                'gsrlimit'     => 15,
                'prop'         => 'imageinfo',
                'iiprop'       => 'url|mime',
                'format'       => 'json',
            ]);

        if ($response->failed()) {
            return null;
        }

        $pages = $response->json('query.pages', []);
        if (empty($pages)) {
            return null;
        }

        usort($pages, fn($a, $b) => ($a['index'] ?? 99) <=> ($b['index'] ?? 99));

        $allowed   = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'];
        $makeLower = strtolower($make);

        // Paso 1: SVG o PNG con make + logo en el título
        foreach ($pages as $page) {
            $mime  = $page['imageinfo'][0]['mime'] ?? '';
            $url   = $page['imageinfo'][0]['url'] ?? null;
            $title = strtolower($page['title'] ?? '');
            if (!$url || !in_array($mime, $allowed)) continue;
            if (str_contains($title, $makeLower) && str_contains($title, 'logo')) {
                return $url;
            }
        }

        // Paso 2: cualquier imagen con "logo" en el título
        foreach ($pages as $page) {
            $mime  = $page['imageinfo'][0]['mime'] ?? '';
            $url   = $page['imageinfo'][0]['url'] ?? null;
            $title = strtolower($page['title'] ?? '');
            if (!$url || !in_array($mime, $allowed)) continue;
            if (str_contains($title, 'logo')) {
                return $url;
            }
        }

        return null;
    }

    public function getFirstImage(string $make, string $model, int $year): ?string
    {
        $cacheKey = 'car_img_' . md5(strtolower("{$make}_{$model}"));

        return Cache::remember($cacheKey, 604800, function () use ($make, $model) {
            return $this->fetchFromWikimedia($make, $model);
        });
    }

    private function fetchFromWikimedia(string $make, string $model): ?string
    {
        $response = Http::timeout(10)
            ->withoutVerifying()
            ->withHeaders(['User-Agent' => 'AutoAlquiler/1.0 (noeliapichardo0624@gmail.com)'])
            ->get('https://commons.wikimedia.org/w/api.php', [
            'action'       => 'query',
            'generator'    => 'search',
            'gsrsearch'    => "{$make} {$model}",
            'gsrnamespace' => 6,
            'gsrlimit'     => 10,
            'prop'         => 'imageinfo',
            'iiprop'       => 'url|mime',
            'format'       => 'json',
        ]);

        if ($response->failed()) {
            return null;
        }

        $pages = $response->json('query.pages', []);

        if (empty($pages)) {
            return null;
        }

        // Sort by search result index
        usort($pages, fn($a, $b) => ($a['index'] ?? 99) <=> ($b['index'] ?? 99));

        $skip = ['gauge', 'interior', 'engine', 'dashboard', 'logo', 'badge',
                 'emblem', 'wheel', 'tire', 'seat', 'door', 'trunk', 'hood'];

        $fallback = null;

        foreach ($pages as $page) {
            $mime  = $page['imageinfo'][0]['mime'] ?? '';
            $url   = $page['imageinfo'][0]['url'] ?? null;
            $title = strtolower($page['title'] ?? '');

            if (! $url || ! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
                continue;
            }

            if ($fallback === null) {
                $fallback = $url;
            }

            $isDetail = false;
            foreach ($skip as $word) {
                if (str_contains($title, $word)) {
                    $isDetail = true;
                    break;
                }
            }

            if (! $isDetail) {
                return $url;
            }
        }

        return $fallback;
    }
}
