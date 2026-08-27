<x-mail::message>
# ¡Tu reserva está confirmada!

Hola **{{ $reservation->user->name }}**,

Tu reserva ha sido confirmada exitosamente. Aquí tienes el resumen:

<x-mail::panel>
**Reserva #{{ $reservation->id }}**
Vehículo: {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
Desde: {{ $reservation->start_date->format('d/m/Y') }}
Hasta: {{ $reservation->end_date->format('d/m/Y') }}
Días: {{ $reservation->start_date->diffInDays($reservation->end_date) }}
Pasajeros: {{ $reservation->passenger_count }}
</x-mail::panel>

**Datos de entrega registrados:**

| Campo | Valor |
|---|---|
| Placa | {{ $reservation->delivery_plate }} |
| Kilometraje | {{ number_format($reservation->delivery_mileage) }} km |
| Combustible | {{ $reservation->delivery_fuel_level }}% |

**Total pagado: $ {{ number_format($reservation->total_cost, 2) }}**

Puedes ver el detalle completo de tu reserva en tu cuenta.

<x-mail::button :url="url('/mis-reservas/' . $reservation->id)">
Ver mi reserva
</x-mail::button>

Gracias por confiar en nosotros. ¡Que disfrutes tu viaje!

{{ config('app.name') }}
</x-mail::message>
