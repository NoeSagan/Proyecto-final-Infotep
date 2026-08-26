<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        return view('admin.vehiculos.mantenimiento', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'maintenance_notes' => ['required', 'string', 'max:1000'],
            'available_from'    => ['required', 'date', 'after:today'],
        ], [
            'maintenance_notes.required' => 'El motivo del mantenimiento es obligatorio.',
            'available_from.required'    => 'La fecha estimada de retorno es obligatoria.',
            'available_from.after'       => 'La fecha de retorno debe ser posterior a hoy.',
        ]);

        $vehicle->update([
            'status'             => 'mantenimiento',
            'maintenance_notes'  => $request->maintenance_notes,
            'available_from'     => $request->available_from,
        ]);

        return redirect()->route('admin.vehiculos.index')
            ->with('success', "Vehículo {$vehicle->plate} puesto en mantenimiento hasta " . \Carbon\Carbon::parse($request->available_from)->format('d/m/Y') . '.');
    }
}
