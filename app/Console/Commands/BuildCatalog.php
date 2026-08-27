<?php

namespace App\Console\Commands;

use App\Services\CarSpecsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class BuildCatalog extends Command
{
    protected $signature   = 'catalog:build {--force : Regenerar aunque ya exista en caché}';
    protected $description = 'Pre-genera el catálogo de CarSpecs en caché para que el catálogo cargue instantáneamente.';

    private const FEATURED_MAKES = [
        'Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi',
        'Ford', 'Chevrolet', 'Nissan', 'Hyundai', 'Volkswagen',
        'Mazda', 'Jeep', 'Tesla', 'Renault', 'Kia',
        'Volvo', 'Mitsubishi', 'Lexus', 'Subaru', 'Peugeot',
    ];

    public function handle(): int
    {
        if (! $this->option('force') && Cache::has('carspecs_catalog_v2')) {
            $count = count(Cache::get('carspecs_catalog_v2'));
            $this->info("Catálogo ya existe en caché ({$count} vehículos). Usa --force para regenerar.");
            return 0;
        }

        $this->info('Obteniendo listado de marcas de CarSpecs…');
        $service  = new CarSpecsService();
        $allMakes = Cache::remember('carspecs_makes', 86400, fn () => $service->getMakes() ?? []);

        if (empty($allMakes)) {
            $this->error('No se pudo obtener las marcas. Verifica la API key.');
            return 1;
        }

        $selected = array_values(array_filter(
            $allMakes,
            fn ($m) => in_array($m['name'], self::FEATURED_MAKES)
        ));

        $this->info('Descargando modelos para ' . count($selected) . ' marcas…');
        $bar = $this->output->createProgressBar(count($selected));
        $bar->start();

        // Fetch secuencial con progreso visible (la API no soporta bien el pool)
        $catalog = [];
        foreach ($selected as $make) {
            $models = Cache::remember(
                "carspecs_models_{$make['id']}",
                86400,
                fn () => $service->getModelsByMake($make['id']) ?? []
            );

            foreach ($models as $model) {
                $catalog[] = $this->buildEntry($make['name'], $model['name']);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        shuffle($catalog);
        Cache::put('carspecs_catalog_v2', $catalog, 86400);

        $this->info("✔ Catálogo generado: " . count($catalog) . " vehículos en caché (24 h).");
        return 0;
    }

    private function buildEntry(string $make, string $model): array
    {
        return [
            'listing_id' => 'cs_' . rtrim(strtr(base64_encode("{$make}||{$model}"), '+/', '-_'), '='),
            'brand'      => $make,
            'model'      => $model,
            'price'      => $this->guessPrice($make),
            'fuel'       => $this->guessFuel($make, $model),
        ];
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
