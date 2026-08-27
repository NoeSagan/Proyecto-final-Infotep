<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Services\AutoScout24Service;

class DashboardController extends Controller
{
    public function index()
    {
        $reservasActivas     = Reservation::where('status', 'confirmada')->count();
        $vehiculosDisponibles = Vehicle::where('status', 'disponible')->count();
        $ganancias           = Reservation::whereIn('status', ['confirmada', 'completada'])->sum('total_cost');
        $pendientes          = Reservation::where('status', 'pendiente')->count();

        $ultimasReservas = Reservation::with('user', 'vehicle')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Marcas disponibles en el mercado (referencia externa)
        $marketMakesCount = null;
        try {
            $service          = new AutoScout24Service();
            $makes            = $service->getMakes();
            $marketMakesCount = is_array($makes) ? count($makes) : null;
        } catch (\Throwable) {
            // API no disponible, continuar sin el dato
        }

        return view('admin.dashboard', compact(
            'reservasActivas',
            'vehiculosDisponibles',
            'ganancias',
            'pendientes',
            'ultimasReservas',
            'marketMakesCount'
        ));
    }
}
