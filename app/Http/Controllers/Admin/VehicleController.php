<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Models\Category;
use App\Models\Vehicle;

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
        return view('admin.vehiculos.create', compact('categories'));
    }

    public function store(StoreVehicleRequest $request)
    {
        Vehicle::create($request->validated());
        return redirect()->route('admin.vehiculos.index')
            ->with('success', 'Vehículo creado correctamente.');
    }

    public function edit(Vehicle $vehicle)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.vehiculos.edit', compact('vehicle', 'categories'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
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
}
