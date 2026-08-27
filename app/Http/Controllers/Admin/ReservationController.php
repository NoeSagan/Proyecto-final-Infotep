<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with('user', 'vehicle');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%")
                                                    ->orWhere('email', 'ilike', "%{$search}%"));
            });
        }

        $reservations = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.reservas.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'vehicle.category', 'extras');
        return view('admin.reservas.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', 'in:confirmada,completada,cancelada'],
        ]);

        $reservation->update(['status' => $request->status]);

        if (in_array($request->status, ['completada', 'cancelada'])) {
            $reservation->vehicle->update(['status' => 'disponible']);
        }

        return redirect()->route('admin.reservas.show', $reservation)
            ->with('success', 'Estado de la reserva actualizado a «' . ucfirst($request->status) . '».');
    }

    public function export(Request $request)
    {
        $query = Reservation::with('user', 'vehicle');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderByDesc('created_at')->get();

        $filename = 'reservas_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reservations) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['#', 'Cliente', 'Email', 'Vehículo', 'Placa', 'Inicio', 'Fin', 'Días', 'Pasajeros', 'Total', 'Estado', 'Creada'], ';');

            foreach ($reservations as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->user->name,
                    $r->user->email,
                    $r->vehicle->brand . ' ' . $r->vehicle->model,
                    $r->vehicle->plate,
                    $r->start_date->format('d/m/Y'),
                    $r->end_date->format('d/m/Y'),
                    $r->start_date->diffInDays($r->end_date),
                    $r->passenger_count,
                    number_format($r->total_cost, 2),
                    ucfirst($r->status),
                    $r->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
