<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Reservas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($reservations as $reservation)
                @php
                    $statusColors = [
                        'pendiente'  => 'bg-yellow-100 text-yellow-800',
                        'confirmada' => 'bg-green-100 text-green-800',
                        'completada' => 'bg-blue-100 text-blue-800',
                        'cancelada'  => 'bg-red-100 text-red-800',
                    ];
                    $color = $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800';
                @endphp

                <div class="bg-white shadow-sm rounded-lg mb-4 overflow-hidden">
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900">
                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                </span>
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $color }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $reservation->vehicle->category->name }} · Placa {{ $reservation->vehicle->plate }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $reservation->start_date->format('d/m/Y') }} → {{ $reservation->end_date->format('d/m/Y') }}
                                &nbsp;·&nbsp; {{ $reservation->start_date->diffInDays($reservation->end_date) }} días
                            </p>
                        </div>

                        <div class="flex flex-col sm:items-end gap-2">
                            <span class="text-lg font-bold text-gray-900">$ {{ number_format($reservation->total_cost, 2) }}</span>
                            <div class="flex gap-2">
                                @if ($reservation->status === 'pendiente')
                                    <a href="{{ route('reservas.pago', $reservation) }}"
                                       class="text-sm bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-3 rounded-lg transition">
                                        Pagar
                                    </a>
                                    <form action="{{ route('mis-reservas.cancel', $reservation) }}" method="POST"
                                          onsubmit="return confirm('¿Cancelar esta reserva?')">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm border border-red-300 text-red-600 hover:bg-red-50 font-medium py-1.5 px-3 rounded-lg transition">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('mis-reservas.show', $reservation) }}"
                                   class="text-sm border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-1.5 px-3 rounded-lg transition">
                                    Ver detalle
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <div class="bg-white shadow-sm rounded-lg py-16 text-center text-gray-500">
                    <p class="text-lg font-medium">No tienes reservas todavía</p>
                    <a href="{{ route('vehiculos.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
                        Ver vehículos disponibles
                    </a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $reservations->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
