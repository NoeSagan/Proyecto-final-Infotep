<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Vehicle;
use App\Services\CarImagesService;
use App\Services\CarSpecsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ImportVehicles extends Command
{
    protected $signature   = 'vehicles:import {count=70 : Cantidad de vehículos a importar}';
    protected $description = 'Importa vehículos desde CarSpecs a la BD con imágenes de Wikimedia.';

    private const FEATURED_MAKES = [
        'Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi',
        'Ford', 'Chevrolet', 'Nissan', 'Hyundai', 'Volkswagen',
        'Mazda', 'Jeep', 'Tesla', 'Renault', 'Kia',
        'Volvo', 'Mitsubishi', 'Lexus', 'Subaru', 'Peugeot',
    ];

    public function handle(): int
    {
        $count = (int) $this->argument('count');

        // 1. Obtener (o construir) el catálogo
        $catalog = $this->getCatalog();

        if (empty($catalog)) {
            $this->error('No se pudo obtener el catálogo. Verifica la API key de CarSpecs.');
            return 1;
        }

        $this->line('  Catálogo: <info>' . count($catalog) . '</info> vehículos.');

        // 2. Excluir los ya importados
        $existingIds = Vehicle::whereNotNull('external_id')->pluck('external_id')->flip()->all();
        $pending = array_values(array_filter($catalog, fn ($v) => !isset($existingIds[$v['listing_id']])));

        if (empty($pending)) {
            $this->warn('Todos los vehículos del catálogo ya están importados.');
            return 0;
        }

        $this->line('  Disponibles para importar: <info>' . count($pending) . '</info>');

        // 3. Seleccionar aleatoriamente
        shuffle($pending);
        $toImport = array_slice($pending, 0, min($count, count($pending)));
        $this->info("Importando " . count($toImport) . " vehículos…");

        // 4. Precargar categorías en memoria (evita N+1 en el bucle)
        $categories = Category::all()->keyBy('name');
        $fallbackCategory = $categories->first();

        $imgService = new CarImagesService();
        $bar        = $this->output->createProgressBar(count($toImport));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->start();

        $imported = 0;
        $skipped  = 0;

        foreach ($toImport as $item) {
            $make      = $item['brand'];
            $model     = $item['model'];
            $listingId = $item['listing_id'];

            $bar->setMessage("{$make} {$model}");

            // Imagen desde Wikimedia (caché 7 días — no recarga si ya existe)
            $imageUrl = null;
            try {
                $imageUrl = $imgService->getFirstImage($make, $model, 2022);
            } catch (\Throwable) {}

            $plate       = 'EXT-' . strtoupper(substr(md5($listingId), 0, 6));
            $categoryId  = $this->resolveCategoryId($make, $model, $categories, $fallbackCategory);
            $fuelType    = $item['fuel'] ?? $this->guessFuel($make, $model);
            $price       = $item['price'] ?? $this->guessPrice($make);

            try {
                Vehicle::create([
                    'external_id'        => $listingId,
                    'brand'              => $make,
                    'model'              => $model,
                    'plate'              => $plate,
                    'price_per_day'      => $price,
                    'status'             => 'disponible',
                    'transmission_type'  => 'automatica',
                    'fuel_type'          => $fuelType,
                    'passenger_capacity' => 5,
                    'category_id'        => $categoryId,
                    'image_url'          => $imageUrl,
                    'current_mileage'    => rand(0, 80000),
                    'current_fuel_level' => 100,
                ]);
                $imported++;
            } catch (\Throwable) {
                $skipped++;
            }

            $bar->advance();
        }

        $bar->setMessage('¡Listo!');
        $bar->finish();
        $this->newLine(2);

        $this->info("✔ Importados: {$imported}" . ($skipped > 0 ? " | Omitidos (duplicados): {$skipped}" : '') . '.');
        $this->line('  Los vehículos ya aparecen en el catálogo y en el panel de admin.');

        return 0;
    }

    // -------------------------------------------------------------------------
    // Catálogo: usa caché si existe, si no lo construye
    // -------------------------------------------------------------------------

    private function getCatalog(): array
    {
        if (Cache::has('carspecs_catalog_v2')) {
            $this->line('  Usando catálogo en caché (carspecs_catalog_v2).');
            return Cache::get('carspecs_catalog_v2');
        }

        $this->info('Catálogo no encontrado en caché. Construyendo desde CarSpecs…');

        $service  = new CarSpecsService();
        $allMakes = Cache::remember('carspecs_makes', 86400, fn () => $service->getMakes() ?? []);

        if (empty($allMakes)) return [];

        $selected = array_values(array_filter(
            $allMakes,
            fn ($m) => in_array($m['name'], self::FEATURED_MAKES)
        ));

        $this->line('  Descargando modelos para ' . count($selected) . ' marcas…');
        $bar = $this->output->createProgressBar(count($selected));
        $bar->start();

        $catalog = [];
        foreach ($selected as $make) {
            $models = Cache::remember(
                "carspecs_models_{$make['id']}",
                86400,
                fn () => $service->getModelsByMake($make['id']) ?? []
            );
            foreach ($models as $model) {
                $catalog[] = [
                    'listing_id' => 'cs_' . rtrim(strtr(base64_encode("{$make['name']}||{$model['name']}"), '+/', '-_'), '='),
                    'brand'      => $make['name'],
                    'model'      => $model['name'],
                    'price'      => $this->guessPrice($make['name']),
                    'fuel'       => $this->guessFuel($make['name'], $model['name']),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        shuffle($catalog);
        Cache::put('carspecs_catalog_v2', $catalog, 86400);
        $this->line('  Catálogo guardado en caché: <info>' . count($catalog) . '</info> vehículos.');

        return $catalog;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function resolveCategoryId(string $make, string $model, $categories, $fallback): int
    {
        $name     = $this->guessCategoryName($make, $model);
        $category = $categories->get($name) ?? $fallback;
        return $category->id;
    }

    private function guessCategoryName(string $make, string $model): string
    {
        $lower = strtolower($model);
        if (preg_match('/suv|rav|cr-v|cx-5|tucson|outlander|x-trail|q5|q7|x5|x3|glc|gle|grand|4runner|forester|sportage|kona|arona|tiguan/i', $lower)) return 'SUV';
        if (preg_match('/van|carnival|sienna|transit|transporter|odyssey|minivan|delica|town/i', $lower)) return 'Van / Minivan';
        if (preg_match('/pickup|hilux|ranger|l200|frontier|colorado|tacoma|tundra|d-max/i', $lower)) return 'Pickup';
        if (in_array($make, ['BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Porsche', 'Ferrari', 'Lamborghini'])) return 'Premium';
        if (preg_match('/corolla|civic|jetta|sentra|elantra|focus|cruze|golf|mazda3|impreza/i', $lower)) return 'Intermedio';
        return 'Económico';
    }

    private function guessPrice(string $make): int
    {
        $luxury  = ['BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Porsche', 'Ferrari', 'Lamborghini'];
        $premium = ['Volvo', 'Acura', 'Infiniti', 'Cadillac', 'Land Rover', 'Jaguar', 'Genesis'];
        $mid     = ['Toyota', 'Honda', 'Mazda', 'Subaru', 'Volkswagen', 'Ford', 'Tesla', 'Jeep'];

        if (in_array($make, $luxury))  return rand(120, 300);
        if (in_array($make, $premium)) return rand(80,  150);
        if (in_array($make, $mid))     return rand(45,   90);
        return rand(28, 60);
    }

    private function guessFuel(string $make, string $model): string
    {
        $lower = strtolower($model . ' ' . $make);
        if ($make === 'Tesla' || str_contains($lower, 'electric')) return 'electrico';
        if (str_contains($lower, 'hybrid') || str_contains($lower, 'prius')) return 'hibrido';
        if (str_contains($lower, 'diesel') || preg_match('/\d+d\b/i', $lower)) return 'diesel';
        return 'gasolina';
    }
}
