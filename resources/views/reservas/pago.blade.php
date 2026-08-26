<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirmación de Pago
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                {{-- Encabezado --}}
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                    <p class="text-white text-sm font-medium">Reserva #{{ $reservation->id }}</p>
                    <h2 class="text-white text-xl font-bold mt-1">
                        {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                    </h2>
                    <p class="text-green-200 text-sm">{{ $reservation->vehicle->category->name }}</p>
                </div>

                <div class="p-6">

                    {{-- Detalle de la reserva --}}
                    <h3 class="text-base font-semibold text-gray-700 mb-4">Detalle de la reserva</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fecha de inicio</span>
                            <span class="font-medium">{{ $reservation->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fecha de fin</span>
                            <span class="font-medium">{{ $reservation->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Días</span>
                            <span class="font-medium">{{ $reservation->start_date->diffInDays($reservation->end_date) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Pasajeros</span>
                            <span class="font-medium">{{ $reservation->passenger_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Precio por día</span>
                            <span class="font-medium">$ {{ number_format($reservation->vehicle->price_per_day, 2) }}</span>
                        </div>
                    </div>

                    {{-- Extras --}}
                    @if ($reservation->extras->isNotEmpty())
                        <h3 class="text-base font-semibold text-gray-700 mb-3">Extras incluidos</h3>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm">
                            @foreach ($reservation->extras as $extra)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $extra->name }} × {{ $extra->pivot->quantity }}</span>
                                    <span class="font-medium">$ {{ number_format($extra->price * $extra->pivot->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Total --}}
                    <div class="flex justify-between items-center border-t pt-4 mb-6">
                        <span class="text-lg font-bold text-gray-800">Total a pagar</span>
                        <span class="text-2xl font-bold text-green-700">$ {{ number_format($reservation->total_cost, 2) }}</span>
                    </div>

                    {{-- Botones --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('reservas.pago.confirm', $reservation) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                                Confirmar y pagar
                            </button>
                        </form>

                        <a href="{{ route('mis-reservas.index') }}"
                           class="flex-1 text-center border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg transition">
                            Pagar más tarde
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 text-center mt-3">
                        Tu reserva está guardada como pendiente. Puedes confirmarla en cualquier momento desde "Mis Reservas".
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
