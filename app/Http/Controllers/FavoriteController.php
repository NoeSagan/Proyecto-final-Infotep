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

    public function toggle(Vehicle $vehicle)
    {
        $user = auth()->user();

        if ($user->favoriteVehicles()->where('vehicle_id', $vehicle->id)->exists()) {
            $user->favoriteVehicles()->detach($vehicle->id);
            $msg = 'Vehículo eliminado de favoritos.';
        } else {
            $user->favoriteVehicles()->syncWithoutDetaching([$vehicle->id]);
            $msg = 'Vehículo añadido a favoritos.';
        }

        return back()->with('success', $msg);
    }
}
