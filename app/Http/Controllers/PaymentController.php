<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class PaymentController extends Controller
{
    public function show(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);
        abort_if($reservation->status !== 'pendiente', 404);

        $reservation->load('vehicle.category', 'extras');
        return view('reservas.pago', compact('reservation'));
    }

    public function confirm(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);
        abort_if($reservation->status !== 'pendiente', 404);

        $vehicle = $reservation->vehicle;

        $reservation->update([
            'status'               => 'confirmada',
            'delivery_plate'       => $vehicle->plate,
            'delivery_mileage'     => $vehicle->current_mileage,
            'delivery_fuel_level'  => $vehicle->current_fuel_level,
        ]);

        $vehicle->update(['status' => 'alquilado']);

        return redirect()->route('mis-reservas.show', $reservation)
            ->with('success', '¡Reserva confirmada! Los datos de entrega han sido registrados.');
    }
}
