<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vehicle;
use App\Services\AutoScout24Service;
use App\Services\CarImagesService;
use App\Services\CarSpecsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class VehicleController extends Controller
{
    private const PER_PAGE = 12;

    // Marcas populares a incluir del catálogo CarSpecs
    private const FEATURED_MAKES = [
        'Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi',
        'Ford', 'Chevrolet', 'Nissan', 'Hyundai', 'Volkswagen',
        'Mazda', 'Jeep', 'Tesla', 'Renault', 'Kia',
        'Volvo', 'Mitsubishi', 'Lexus', 'Subaru', 'Peugeot',
    ];

    // -------------------------------------------------------------------------
    // Catálogo público
    // -------------------------------------------------------------------------

    public function index(Request $request)
    {
        $request->validate([
            'start_date'        => ['nullable', 'date', 'after_or_equal:today'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'passengers'        => ['nullable', 'integer', 'min:1'],
            'category_id'       => ['nullable', 'exists:categories,id'],
            'transmission_type' => ['nullable', 'in:manual,automatica'],
            'fuel_type'         => ['nullable', 'in:gasolina,diesel,hibrido,electrico'],
            'price_min'         => ['nullable', 'numeric', 'min:0'],
            'price_max'         => ['nullable', 'numeric', 'min:0'],
            'search'            => ['nullable', 'string', 'max:100'],
        ]);

        $categories = Category::orderBy('name')->get();

        // 1. AutoScout24 (listings reales con precio e imágenes)
        $result = $this->fetchAutoScout24Catalog($request);

        // 2. CarSpecs (marcas × modelos — miles de vehículos)
        if ($result === null) {
            $result = $this->fetchCarSpecsCatalog($request);
        }

        if ($result !== null) {
            return view('vehiculos.index', array_merge($result, compact('categories')));
        }

        // 3. Fallback: base de datos propia
        $query = Vehicle::with('category')->where('status', 'disponible');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('passengers')) {
            $query->where('passenger_capacity', '>=', $request->passengers);
        }
        if ($request->filled('transmission_type')) {
            $query->where('transmission_type', $request->transmission_type);
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }
        if ($request->filled('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDoesntHave('reservations', function ($q) use ($request) {
                $q->whereIn('status', ['pendiente', 'confirmada'])
                  ->where('start_date', '<=', $request->end_date)
                  ->where('end_date', '>=', $request->start_date);
            });
        }
        if ($request->filled('search')) {
            $term = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(brand) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(model) LIKE ?', [$term]);
            });
        }

        $vehicles = $query->orderBy('brand')->paginate(self::PER_PAGE)->withQueryString();
        $this->loadMissingImages($vehicles->items());

        return view('vehiculos.index', compact('vehicles', 'categories'));
    }

    // -------------------------------------------------------------------------
    // Detalle — vehículo de la BD
    // -------------------------------------------------------------------------

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('category');

        if (! $vehicle->image_url) {
            $this->loadMissingImages([$vehicle]);
            $vehicle->refresh();
        }

        $extras = \App\Models\Extra::orderBy('name')->get();
        return view('vehiculos.show', compact('vehicle', 'extras'));
    }

    // -------------------------------------------------------------------------
    // Detalle — listing externo (AutoScout24 o CarSpecs) → import on demand
    // -------------------------------------------------------------------------

    public function showListing(string $listingId)
    {
        // Ya importado → redirigir
        $existing = Vehicle::where('external_id', $listingId)->first();
        if ($existing) {
            return redirect()->route('vehiculos.show', $existing);
        }

        $vehicle = null;

        if (str_starts_with($listingId, 'cs_')) {
            // Vehículo de CarSpecs
            $vehicle = $this->importCarSpecsVehicle($listingId);
        } else {
            // Vehículo de AutoScout24
            $vehicle = $this->importAutoScout24Vehicle($listingId);
        }

        if (! $vehicle) {
            abort(404, 'Vehículo no encontrado.');
        }

        return redirect()->route('vehiculos.show', $vehicle);
    }

    // =========================================================================
    // FUENTE 1: AutoScout24
    // =========================================================================

    private function fetchAutoScout24Catalog(Request $request): ?array
    {
        try {
            $service = new AutoScout24Service();
            $page    = max(1, (int) $request->input('page', 1));
            $params  = ['pageSize' => self::PER_PAGE, 'page' => $page];

            if ($request->filled('transmission_type')) {
                $params['transmissionType'] = $request->transmission_type === 'automatica' ? 'A' : 'M';
            }
            if ($request->filled('fuel_type')) {
                $code = $this->fuelToCode($request->fuel_type);
                if ($code) $params['fuel'] = $code;
            }
            if ($request->filled('price_min')) {
                $params['priceFrom'] = (int) $request->price_min;
            }
            if ($request->filled('price_max')) {
                $params['priceTo'] = (int) $request->price_max;
            }
            if ($request->filled('passengers')) {
                $params['seats'] = (int) $request->passengers;
            }

            $result = $service->getListings($params);
            if (! $result || empty($result['listings'])) return null;

            $totalCount = (int) ($result['totalCount'] ?? count($result['listings']));
            $mapped     = collect($result['listings'])->map(fn ($l) => $this->mapAutoScout24Listing($l));

            $vehicles = new LengthAwarePaginator(
                $mapped, $totalCount, self::PER_PAGE, $page,
                ['path' => $request->url(), 'query' => $request->except('page')]
            );

            return compact('vehicles');

        } catch (\Throwable) {
            return null;
        }
    }

    private function mapAutoScout24Listing(array $l): \stdClass
    {
        $v = new \stdClass();
        $v->is_api     = true;
        $v->listing_id = $l['id'] ?? null;
        $v->id         = null;
        $v->brand      = $l['vehicle']['make'] ?? 'Auto';
        $v->model      = $l['vehicle']['model'] ?? 'Modelo';

        $reg    = $l['vehicle']['firstRegistration'] ?? null;
        $v->year = is_array($reg) ? ($reg['year'] ?? null)
                 : (is_string($reg) ? (int) substr($reg, 0, 4) : null);

        $salePrice        = $l['prices']['public']['priceRaw'] ?? $l['prices']['public']['value'] ?? 0;
        $v->price_per_day = $salePrice > 0 ? max(25, min(400, (int) round($salePrice / 300))) : 50;
        $v->image_url     = ! empty($l['images']) ? $l['images'][0] : null;

        $v->passenger_capacity = $l['vehicle']['seats'] ?? 5;
        $v->transmission_type  = $this->mapTransmission($l['vehicle']['transmissionType'] ?? '');
        $v->fuel_type          = $this->codeToFuel($l['vehicle']['fuelCategory']['code'] ?? $l['vehicle']['fuel'] ?? '');

        $cat       = new \stdClass();
        $cat->name = $this->mapBodyType($l['vehicle']['bodyType'] ?? '');
        $v->category  = $cat;
        $v->status    = 'disponible';
        $v->key_features = null;

        return $v;
    }

    private function importAutoScout24Vehicle(string $listingId): ?Vehicle
    {
        try {
            $service = new AutoScout24Service();
            $listing = $service->getListing($listingId);
            if (! $listing) return null;

            $mapped = $this->mapAutoScout24Listing($listing);
            return $this->createVehicleFromMapped($mapped, $listingId);

        } catch (\Throwable) {
            return null;
        }
    }

    // =========================================================================
    // FUENTE 2: CarSpecs (marcas × modelos)
    // =========================================================================

    private function fetchCarSpecsCatalog(Request $request): ?array
    {
        try {
            // Solo usa el catálogo si ya fue pre-generado con `php artisan catalog:build`
            // Nunca lo construye inline (tomaría 30+ segundos)
            if (! Cache::has('carspecs_catalog_v2')) {
                return null;
            }

            // El comando guarda arrays simples; convertir a stdClass para la vista
            $raw = Cache::get('carspecs_catalog_v2');
            if (empty($raw)) return null;

            $allListings = array_map(fn ($item) => is_array($item)
                ? $this->specsToCatalogVehicle($item['brand'], $item['model'])
                : $item,
                $raw
            );

            // Aplicar filtros en memoria
            $filtered = $this->applyCarSpecsFilters($allListings, $request);

            if (empty($filtered)) return null;

            $page  = max(1, (int) $request->input('page', 1));
            $total = count($filtered);
            $items = array_slice($filtered, ($page - 1) * self::PER_PAGE, self::PER_PAGE);

            // Cargar imágenes solo para los 12 vehículos de la página actual (Wikimedia, cacheado)
            $imgService = new CarImagesService();
            foreach ($items as $item) {
                if (! $item->image_url) {
                    try {
                        $item->image_url = $imgService->getFirstImage($item->brand, $item->model, 2022);
                    } catch (\Throwable) {}
                }
            }

            $vehicles = new LengthAwarePaginator(
                collect($items), $total, self::PER_PAGE, $page,
                ['path' => $request->url(), 'query' => $request->except('page')]
            );

            return compact('vehicles');

        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Construye el catálogo completo de CarSpecs en una sola llamada (con pool paralelo).
     * Solo se ejecuta una vez cada 24 h.
     */
    private function buildCarSpecsCatalog(): array
    {
        $service  = new CarSpecsService();
        $allMakes = Cache::remember('carspecs_makes', 86400, fn () => $service->getMakes() ?? []);

        if (empty($allMakes)) return [];

        // Filtrar a las marcas destacadas
        $selected = array_values(array_filter(
            $allMakes,
            fn ($m) => in_array($m['name'], self::FEATURED_MAKES)
        ));

        // Fetch paralelo de modelos para todas las marcas
        $modelsByMake = $service->getModelsInParallel($selected);

        $catalog = [];

        foreach ($selected as $make) {
            $models = $modelsByMake[$make['id']] ?? [];
            foreach ($models as $model) {
                $catalog[] = $this->specsToCatalogVehicle($make['name'], $model['name']);
            }
        }

        // Mezclar para que no aparezcan todas las Toyota juntas
        shuffle($catalog);

        return $catalog;
    }

    private function specsToCatalogVehicle(string $make, string $model): \stdClass
    {
        $v = new \stdClass();

        // Codificar make/model en listing_id (base64 URL-safe, sin caracteres problemáticos)
        $v->is_api     = true;
        $v->listing_id = 'cs_' . rtrim(strtr(base64_encode("{$make}||{$model}"), '+/', '-_'), '=');
        $v->id         = null;

        $v->brand = $make;
        $v->model = $model;
        $v->year  = null;

        $v->price_per_day      = $this->guessPriceFromMake($make);
        $v->image_url          = null; // se carga por página con Wikimedia
        $v->passenger_capacity = 5;
        $v->transmission_type  = 'automatica';
        $v->fuel_type          = $this->guessFuelFromModel($make, $model);

        $cat       = new \stdClass();
        $cat->name = $this->guessCategoryName($make, $model);
        $v->category    = $cat;
        $v->status      = 'disponible';
        $v->key_features = null;

        return $v;
    }

    private function applyCarSpecsFilters(array $listings, Request $request): array
    {
        return array_values(array_filter($listings, function ($v) use ($request) {
            if ($request->filled('search')) {
                $term = strtolower($request->search);
                if (!str_contains(strtolower($v->brand . ' ' . $v->model), $term)) return false;
            }
            if ($request->filled('transmission_type') && $v->transmission_type !== $request->transmission_type) {
                return false;
            }
            if ($request->filled('fuel_type') && $v->fuel_type !== $request->fuel_type) {
                return false;
            }
            if ($request->filled('price_min') && $v->price_per_day < $request->price_min) {
                return false;
            }
            if ($request->filled('price_max') && $v->price_per_day > $request->price_max) {
                return false;
            }
            if ($request->filled('passengers') && $v->passenger_capacity < $request->passengers) {
                return false;
            }
            return true;
        }));
    }

    private function importCarSpecsVehicle(string $listingId): ?Vehicle
    {
        try {
            $b64     = strtr(substr($listingId, 3), '-_', '+/');
            $decoded = base64_decode($b64);
            [$make, $model] = explode('||', $decoded, 2);

            $imageUrl = null;
            try {
                $imageUrl = (new CarImagesService())->getFirstImage($make, $model, 2022);
            } catch (\Throwable) {}

            $plate = 'EXT-' . strtoupper(substr(md5($listingId), 0, 6));

            return Vehicle::create([
                'external_id'        => $listingId,
                'brand'              => $make,
                'model'              => $model,
                'plate'              => $plate,
                'price_per_day'      => $this->guessPriceFromMake($make),
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => $this->guessFuelFromModel($make, $model),
                'passenger_capacity' => 5,
                'category_id'        => $this->guessCategoryId($make, $model),
                'image_url'          => $imageUrl,
                'current_mileage'    => 0,
                'current_fuel_level' => 100,
            ]);
        } catch (\Throwable) {
            return null;
        }
    }

    // =========================================================================
    // Helpers compartidos
    // =========================================================================

    private function createVehicleFromMapped(\stdClass $mapped, string $externalId): Vehicle
    {
        $plate = 'EXT-' . strtoupper(substr(md5($externalId), 0, 6));

        return Vehicle::create([
            'external_id'        => $externalId,
            'brand'              => $mapped->brand,
            'model'              => $mapped->model,
            'year'               => $mapped->year,
            'plate'              => $plate,
            'price_per_day'      => $mapped->price_per_day,
            'status'             => 'disponible',
            'transmission_type'  => $mapped->transmission_type,
            'fuel_type'          => $mapped->fuel_type,
            'passenger_capacity' => $mapped->passenger_capacity,
            'category_id'        => $this->guessCategoryId($mapped->brand, $mapped->model),
            'image_url'          => $mapped->image_url,
            'current_mileage'    => 0,
            'current_fuel_level' => 100,
        ]);
    }

    private function guessPriceFromMake(string $make): int
    {
        $luxury  = ['BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Porsche', 'Ferrari', 'Lamborghini', 'Maserati', 'Bentley', 'Rolls-Royce'];
        $premium = ['Volvo', 'Acura', 'Infiniti', 'Cadillac', 'Land Rover', 'Jaguar', 'Genesis', 'Lincoln'];
        $mid     = ['Toyota', 'Honda', 'Mazda', 'Subaru', 'Volkswagen', 'Ford', 'Tesla', 'Jeep'];

        if (in_array($make, $luxury))  return rand(120, 300);
        if (in_array($make, $premium)) return rand(80,  150);
        if (in_array($make, $mid))     return rand(45,  90);
        return rand(28, 60);
    }

    private function guessFuelFromModel(string $make, string $model): string
    {
        $lower = strtolower($model . ' ' . $make);
        if ($make === 'Tesla' || str_contains($lower, 'electric') || str_contains($lower, 'ev ')) return 'electrico';
        if (str_contains($lower, 'hybrid') || str_contains($lower, 'prius') || str_contains($lower, 'insight')) return 'hibrido';
        if (str_contains($lower, 'diesel') || str_contains($lower, ' d ') || preg_match('/\d+d\b/i', $lower)) return 'diesel';
        return 'gasolina';
    }

    private function guessCategoryName(string $make, string $model): string
    {
        $lower = strtolower($model);
        if (preg_match('/suv|rav|cr-v|cx-5|tucson|outlander|x-trail|q5|q7|x5|x3|glc|gle|grand|4runner|forester|sportage|kona|arona|tiguan/i', $lower)) return 'SUV';
        if (preg_match('/van|carnival|sienna|transit|transporter|caravelle|odyssey|minivan|delica|town/i', $lower)) return 'Van / Minivan';
        if (preg_match('/pickup|hilux|ranger|l200|frontier|colorado|tacoma|tundra|d-max/i', $lower)) return 'Pickup';
        if (in_array($make, ['BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Porsche', 'Ferrari', 'Lamborghini'])) return 'Premium';
        if (preg_match('/corolla|civic|jetta|sentra|elantra|focus|cruze|golf|mazda3|impreza/i', $lower)) return 'Intermedio';
        return 'Económico';
    }

    private function guessCategoryId(string $make, string $model): int
    {
        $name     = $this->guessCategoryName($make, $model);
        $category = Category::where('name', $name)->first()
                 ?? Category::orderBy('id')->first();
        return $category->id;
    }

    private function mapTransmission(string $code): string
    {
        return in_array(strtoupper($code), ['A', 'AUTOMATIC']) ? 'automatica' : 'manual';
    }

    private function mapBodyType(string $bodyType): string
    {
        return match ($bodyType) {
            'Limousine' => 'Sedán',   'Sedan'   => 'Sedán',
            'Compact'   => 'Compacto', 'SmallCar' => 'City Car',
            'SUV'       => 'SUV',      'OffRoad'  => 'Off-Road',
            'Coupe'     => 'Coupé',    'Cabrio'   => 'Descapotable',
            'Pickup'    => 'Pickup',
            'Van'       => 'Van',      'Minibus'  => 'Minivan',
            'Motorbike' => 'Moto',
            default     => $bodyType ?: 'Auto',
        };
    }

    private function fuelToCode(string $fuel): string
    {
        return match ($fuel) {
            'gasolina'  => 'P',
            'diesel'    => 'D',
            'electrico' => 'E',
            'hibrido'   => 'H',
            default     => '',
        };
    }

    private function codeToFuel(string $code): string
    {
        return match (strtoupper($code)) {
            'P', 'PETROL', 'GASOLINE', 'B' => 'gasolina',
            'D', 'DIESEL'                   => 'diesel',
            'E', 'ELECTRIC'                 => 'electrico',
            'H', 'HYBRID', 'MHEV', 'PHEV'  => 'hibrido',
            default                         => 'gasolina',
        };
    }

    private function loadMissingImages(array $vehicles): void
    {
        $missing = array_filter($vehicles, fn ($v) => ! $v->image_url);
        if (empty($missing)) return;

        try {
            $service = new CarImagesService();
            foreach ($missing as $vehicle) {
                $url = $service->getFirstImage(
                    $vehicle->brand, $vehicle->model, $vehicle->year ?? (int) date('Y')
                );
                if ($url) {
                    $vehicle->updateQuietly(['image_url' => $url]);
                    $vehicle->image_url = $url;
                }
            }
        } catch (\Throwable) {}
    }
}
