<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmed;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function show(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);

        if ($reservation->status !== 'pendiente') {
            return redirect()->route('mis-reservas.show', $reservation)
                ->with('info', 'Esta reserva ya fue procesada.');
        }

        $reservation->load('vehicle.category', 'extras');
        return view('reservas.pago', compact('reservation'));
    }

    public function confirm(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);

        if ($reservation->status !== 'pendiente') {
            return redirect()->route('mis-reservas.show', $reservation)
                ->with('info', 'Esta reserva ya fue confirmada anteriormente.');
        }

        $vehicle = $reservation->vehicle;

        $reservation->update([
            'status'               => 'confirmada',
            'delivery_plate'       => $vehicle->plate,
            'delivery_mileage'     => $vehicle->current_mileage,
            'delivery_fuel_level'  => $vehicle->current_fuel_level,
        ]);

        $vehicle->update(['status' => 'alquilado']);

        $reservation->load('user', 'extras');
        try {
            Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));
        } catch (\Throwable) {
            // Email failure should not block the user flow
        }

        return redirect()->route('mis-reservas.show', $reservation)
            ->with('success', '¡Reserva confirmada! Los datos de entrega han sido registrados. Te enviamos un correo de confirmación.');
    }
}
