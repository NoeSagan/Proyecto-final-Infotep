<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Vehículos más alquilados
        $masAlquilados = Vehicle::withCount(['reservations as total_reservations' => function ($q) {
            $q->whereIn('status', ['confirmada', 'completada']);
        }])
        ->orderByDesc('total_reservations')
        ->limit(5)
        ->get();

        // Ingresos por mes (últimos 6 meses)
        $ingresosPorMes = Reservation::whereIn('status', ['confirmada', 'completada'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as mes, SUM(total_cost) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Ocupación por categoría
        $porCategoria = Category::withCount(['vehicles as total' => fn($q) => $q])
            ->withCount(['vehicles as alquilados' => fn($q) => $q->where('status', 'alquilado')])
            ->get();

        return view('admin.reportes.index', compact('masAlquilados', 'ingresosPorMes', 'porCategoria'));
    }
}
