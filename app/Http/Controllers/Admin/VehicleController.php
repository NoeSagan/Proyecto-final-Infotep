<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Models\Category;
use App\Models\Vehicle;
use App\Services\AutoDevService;
use App\Services\CarImagesService;
use App\Services\CarSpecsService;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('category')->orderBy('brand')->paginate(15);
        return view('admin.vehiculos.index', compact('vehicles'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $makes      = $this->fetchMakes();
        return view('admin.vehiculos.create', compact('categories', 'makes'));
    }

    public function store(StoreVehicleRequest $request)
    {
        $data      = $request->validated();
        $imageUrl  = $this->fetchImage($data['brand'], $data['model'], $data['year'] ?? null);
        if ($imageUrl) {
            $data['image_url'] = $imageUrl;
        }

        Vehicle::create($data);
        return redirect()->route('admin.vehiculos.index')
            ->with('success', 'Vehículo creado correctamente.');
    }

    public function edit(Vehicle $vehicle)
    {
        $categories = Category::orderBy('name')->get();
        $makes      = $this->fetchMakes();
        return view('admin.vehiculos.edit', compact('vehicle', 'categories', 'makes'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        // Refresh image if brand/model/year changed or no image stored yet
        if (! $vehicle->image_url || $vehicle->brand !== $data['brand'] || $vehicle->model !== $data['model']) {
            $imageUrl = $this->fetchImage($data['brand'], $data['model'], $data['year'] ?? null);
            if ($imageUrl) {
                $data['image_url'] = $imageUrl;
            }
        }

        $vehicle->update($data);
        return redirect()->route('admin.vehiculos.index')
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->reservations()->whereIn('status', ['pendiente', 'confirmada'])->exists()) {
            return redirect()->route('admin.vehiculos.index')
                ->with('error', 'No se puede eliminar el vehículo porque tiene reservas activas.');
        }

        $vehicle->delete();
        return redirect()->route('admin.vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }

    public function vinLookup(string $vin)
    {
        try {
            $service = new AutoDevService();
            $data    = $service->lookupByVin($vin);
        } catch (\Throwable) {
            $data = null;
        }

        if (! $data) {
            return response()->json(['error' => 'No se encontraron datos para ese VIN.'], 404);
        }

        return response()->json($data);
    }

    private function fetchImage(string $brand, string $model, ?int $year): ?string
    {
        try {
            $service = new CarImagesService();
            return $service->getFirstImage($brand, $model, $year ?? date('Y'));
        } catch (\Throwable) {
            return null;
        }
    }

    private function fetchMakes(): array
    {
        try {
            $service = new CarSpecsService();
            $makes   = $service->getMakes();
            return is_array($makes) ? array_column($makes, 'name') : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
