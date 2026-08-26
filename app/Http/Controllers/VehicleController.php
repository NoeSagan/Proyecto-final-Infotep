<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date'  => ['nullable', 'date', 'after_or_equal:today'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'passengers'  => ['nullable', 'integer', 'min:1'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $query = Vehicle::with('category')->where('status', 'disponible');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('passengers')) {
            $query->where('passenger_capacity', '>=', $request->passengers);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDoesntHave('reservations', function ($q) use ($request) {
                $q->whereIn('status', ['pendiente', 'confirmada'])
                  ->where('start_date', '<=', $request->end_date)
                  ->where('end_date', '>=', $request->start_date);
            });
        }

        $vehicles   = $query->orderBy('brand')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('vehiculos.index', compact('vehicles', 'categories'));
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('category');
        return view('vehiculos.show', compact('vehicle'));
    }
}
