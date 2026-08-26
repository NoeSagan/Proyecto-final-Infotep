<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class FavoriteController extends Controller
{
    public function index()
    {
        $vehicles = auth()->user()->favoriteVehicles()
            ->with('category')
            ->orderBy('brand')
            ->paginate(12);

        return view('favoritos.index', compact('vehicles'));
    }

    public function store(Vehicle $vehicle)
    {
        auth()->user()->favoriteVehicles()->syncWithoutDetaching([$vehicle->id]);

        return back()->with('success', 'Vehículo añadido a favoritos.');
    }

    public function destroy(Vehicle $vehicle)
    {
        auth()->user()->favoriteVehicles()->detach($vehicle->id);

        return back()->with('success', 'Vehículo eliminado de favoritos.');
    }
}
