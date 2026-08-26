<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-700">&larr; Volver</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                {{-- Encabezado --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-white text-2xl font-bold">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </h1>
                            @if ($vehicle->model_alternative)
                                <p class="text-blue-200 text-sm mt-1">{{ $vehicle->model_alternative }}</p>
                            @endif
                            <span class="mt-2 inline-block bg-white/20 text-white text-xs font-medium px-3 py-1 rounded-full">
                                {{ $vehicle->category->name }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-200 text-sm">Precio por día</p>
                            <p class="text-white text-3xl font-bold">$ {{ number_format($vehicle->price_per_day, 2) }}</p>
                            <p class="text-blue-200 text-xs mt-1">Placa: {{ $vehicle->plate }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">

                    {{-- Estado --}}
                    @php
                        $statusColors = [
                            'disponible'    => 'bg-green-100 text-green-800',
                            'alquilado'     => 'bg-blue-100 text-blue-800',
                            'mantenimiento' => 'bg-yellow-100 text-yellow-800',
                        ];
                        $color = $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <div class="mb-6 flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-500">Disponibilidad:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($vehicle->status) }}
                        </span>
                    </div>

                    {{-- Ficha técnica --}}
                    <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Ficha técnica</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-6">

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transmisión</p>
                            <p class="mt-1 text-gray-900 font-medium">{{ ucfirst($vehicle->transmission_type) }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Combustible</p>
                            <p class="mt-1 text-gray-900 font-medium">{{ ucfirst($vehicle->fuel_type) }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pasajeros</p>
                            <p class="mt-1 text-gray-900 font-medium">{{ $vehicle->passenger_capacity }}</p>
                        </div>

                        @if ($vehicle->luggage_capacity !== null)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Maletas</p>
                                <p class="mt-1 text-gray-900 font-medium">{{ $vehicle->luggage_capacity }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kilometraje</p>
                            <p class="mt-1 text-gray-900 font-medium">{{ number_format($vehicle->current_mileage) }} km</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Combustible actual</p>
                            <p class="mt-1 text-gray-900 font-medium">{{ $vehicle->current_fuel_level }}%</p>
                        </div>
                    </div>

                    {{-- Prestaciones --}}
                    @if ($vehicle->key_features)
                        <h3 class="text-base font-semibold text-gray-700 mb-3 border-b pb-2">Prestaciones</h3>
                        <div class="mb-6">
                            @foreach (explode(',', $vehicle->key_features) as $feature)
                                <span class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full mr-2 mb-2">
                                    {{ trim($feature) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Acciones --}}
                    @php
                        $esFavorito = auth()->user()->favoriteVehicles()->where('vehicle_id', $vehicle->id)->exists();
                    @endphp

                    <div class="border-t pt-6 flex items-center justify-between gap-3">
                        {{-- Botón favorito --}}
                        @if ($esFavorito)
                            <form action="{{ route('favoritos.destroy', $vehicle) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-1 text-sm border border-red-300 text-red-600 hover:bg-red-50 font-medium py-2 px-4 rounded-lg transition">
                                    ♥ Quitar de favoritos
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favoritos.store', $vehicle) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-1 text-sm border border-gray-300 text-gray-600 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg transition">
                                    ♡ Añadir a favoritos
                                </button>
                            </form>
                        @endif

                        {{-- Botón reservar --}}
                        @if ($vehicle->status === 'disponible')
                            <a href="{{ route('vehiculos.reservar', $vehicle) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-8 rounded-lg transition">
                                Reservar este vehículo
                            </a>
                        @else
                            <p class="text-gray-500 italic text-sm">Este vehículo no está disponible para reservas en este momento.</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
