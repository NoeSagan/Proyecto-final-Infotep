<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $reservasActivas     = Reservation::where('status', 'confirmada')->count();
        $vehiculosDisponibles = Vehicle::where('status', 'disponible')->count();
        $ganancias           = Reservation::whereIn('status', ['confirmada', 'completada'])->sum('total_cost');

        $ultimasReservas = Reservation::with('user', 'vehicle')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'reservasActivas',
            'vehiculosDisponibles',
            'ganancias',
            'ultimasReservas'
        ));
    }
}
