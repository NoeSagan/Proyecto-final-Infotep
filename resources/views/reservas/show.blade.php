<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mis-reservas.index') }}" class="text-gray-500 hover:text-gray-700">&larr; Mis Reservas</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de Reserva #{{ $reservation->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                {{-- Encabezado --}}
                @php
                    $headerColors = [
                        'pendiente'  => 'from-yellow-500 to-yellow-600',
                        'confirmada' => 'from-green-600 to-green-700',
                        'completada' => 'from-blue-600 to-blue-700',
                        'cancelada'  => 'from-red-500 to-red-600',
                    ];
                    $hColor = $headerColors[$reservation->status] ?? 'from-gray-500 to-gray-600';
                @endphp

                <div class="bg-gradient-to-r {{ $hColor }} px-6 py-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-white/80 text-sm">Reserva #{{ $reservation->id }}</p>
                            <h2 class="text-white text-xl font-bold">
                                {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                            </h2>
                            <p class="text-white/70 text-sm">{{ $reservation->vehicle->category->name }}</p>
                        </div>
                        <span class="bg-white/20 text-white text-sm font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">

                    {{-- Fechas y pasajeros --}}
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Detalle de la reserva</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400">Inicio</p>
                            <p class="font-medium text-gray-800">{{ $reservation->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Fin</p>
                            <p class="font-medium text-gray-800">{{ $reservation->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Días</p>
                            <p class="font-medium text-gray-800">{{ $reservation->start_date->diffInDays($reservation->end_date) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Pasajeros</p>
                            <p class="font-medium text-gray-800">{{ $reservation->passenger_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Precio / día</p>
                            <p class="font-medium text-gray-800">$ {{ number_format($reservation->vehicle->price_per_day, 2) }}</p>
                        </div>
                    </div>

                    {{-- Extras --}}
                    @if ($reservation->extras->isNotEmpty())
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Extras</h3>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm">
                            @foreach ($reservation->extras as $extra)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $extra->name }} × {{ $extra->pivot->quantity }}</span>
                                    <span class="font-medium text-gray-800">$ {{ number_format($extra->price * $extra->pivot->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Total --}}
                    <div class="flex justify-between items-center border-t pt-4 mb-6">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-gray-900">$ {{ number_format($reservation->total_cost, 2) }}</span>
                    </div>

                    {{-- Datos de entrega (solo si está confirmada o completada) --}}
                    @if (in_array($reservation->status, ['confirmada', 'completada']))
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Datos de entrega</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Placa</p>
                                <p class="font-mono font-medium text-gray-800">{{ $reservation->delivery_plate }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Kilometraje</p>
                                <p class="font-medium text-gray-800">{{ number_format($reservation->delivery_mileage) }} km</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Combustible</p>
                                <p class="font-medium text-gray-800">{{ $reservation->delivery_fuel_level }}%</p>
                            </div>
                        </div>
                    @endif

                    {{-- Acción pendiente --}}
                    @if ($reservation->status === 'pendiente')
                        <div class="flex justify-end">
                            <a href="{{ route('reservas.pago', $reservation) }}"
                               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                Ir a pagar
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
