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

        return redirect()->route('admin.reservas.show', $reservation)
            ->with('success', 'Estado de la reserva actualizado a «' . ucfirst($request->status) . '».');
    }
}
