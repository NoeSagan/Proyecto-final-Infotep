<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Extra;
use App\Models\Reservation;
use App\Models\Vehicle;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        if ($vehicle->status !== 'disponible') {
            return redirect()->route('vehiculos.show', $vehicle)
                ->with('error', 'Este vehículo no está disponible para reservas.');
        }

        $extras = Extra::orderBy('name')->get();
        return view('reservas.create', compact('vehicle', 'extras'));
    }

    public function store(StoreReservationRequest $request, Vehicle $vehicle)
    {
        if ($vehicle->status !== 'disponible') {
            return back()->with('error', 'Este vehículo ya no está disponible.');
        }

        // Verificar solapamiento con reservas activas
        $conflict = $vehicle->reservations()
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($conflict) {
            return back()->withInput()
                ->with('error', 'El vehículo no está disponible en esas fechas. Por favor elige otras.');
        }

        // Validar capacidad de pasajeros
        if ($request->passenger_count > $vehicle->passenger_capacity) {
            return back()->withInput()
                ->withErrors(['passenger_count' => "Este vehículo tiene capacidad para {$vehicle->passenger_capacity} pasajeros."]);
        }

        $days = Carbon::parse($request->start_date)->diffInDays($request->end_date);

        // Calcular costo de extras
        $extrasCost     = 0;
        $extrasToAttach = [];
        if ($request->filled('extras')) {
            $extrasData = Extra::whereIn('id', array_keys($request->extras))->get()->keyBy('id');
            foreach ($request->extras as $extraId => $quantity) {
                if ($quantity > 0 && isset($extrasData[$extraId])) {
                    $extrasCost += $extrasData[$extraId]->price * $quantity;
                    $extrasToAttach[$extraId] = ['quantity' => $quantity];
                }
            }
        }

        $reservation = Reservation::create([
            'user_id'         => auth()->id(),
            'vehicle_id'      => $vehicle->id,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'passenger_count' => $request->passenger_count,
            'total_cost'      => $days * $vehicle->price_per_day + $extrasCost,
            'status'          => 'pendiente',
        ]);

        if (!empty($extrasToAttach)) {
            $reservation->extras()->attach($extrasToAttach);
        }

        return redirect()->route('reservas.pago', $reservation);
    }

    public function index()
    {
        $reservations = auth()->user()->reservations()
            ->with('vehicle.category')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservas.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);

        $reservation->load('vehicle.category', 'extras');
        return view('reservas.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);
        abort_if($reservation->status !== 'pendiente', 403);

        $reservation->update(['status' => 'cancelada']);
        $reservation->vehicle->update(['status' => 'disponible']);

        return redirect()->route('mis-reservas.index')
            ->with('success', 'Reserva cancelada correctamente.');
    }
}
