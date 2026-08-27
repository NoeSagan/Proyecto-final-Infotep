<x-app-layout>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Volver --}}
    <a href="{{ route('vehiculos.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver al catálogo
    </a>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- Imagen --}}
        @if ($vehicle->image_url)
            <div class="bg-gray-100 h-48 sm:h-64 overflow-hidden">
                <img src="{{ $vehicle->image_url }}"
                     alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                     class="w-full h-full object-contain"
                     onerror="this.parentElement.style.display='none'">
            </div>
        @endif

        <div class="p-6">

            {{-- Encabezado: título + precio + corazón --}}
            <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
                <div>
                    {{-- Pastillas --}}
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $vehicle->category->name }}
                        </span>
                        @php
                            $statusLabel = match($vehicle->status) {
                                'disponible'                     => 'Disponible',
                                'alquilado'                      => 'Alquilado',
                                'mantenimiento','en_mantenimiento' => 'Mantenimiento',
                                default                          => ucfirst($vehicle->status),
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Nombre + año --}}
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $vehicle->brand }} {{ $vehicle->model }}
                        @if ($vehicle->year)
                            <span class="text-gray-400 font-normal text-base ml-1">{{ $vehicle->year }}</span>
                        @endif
                    </h1>
                    @if ($vehicle->model_alternative)
                        <p class="text-gray-400 text-sm mt-0.5">{{ $vehicle->model_alternative }}</p>
                    @endif
                </div>

                {{-- Precio + corazón --}}
                <div class="flex items-center gap-3 shrink-0">

                    {{-- Precio --}}
                    <div class="text-right">
                        <p class="text-gray-400 text-xs mb-0.5">Precio por día</p>
                        <p class="text-3xl font-bold text-gray-900">$ {{ number_format($vehicle->price_per_day, 2) }}</p>
                    </div>

                    {{-- Corazón (a la derecha del precio) --}}
                    @auth
                        @php $esFav = auth()->user()->favoriteVehicles()->where('vehicle_id', $vehicle->id)->exists(); @endphp
                        <form action="{{ route('favoritos.toggle', $vehicle) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-9 h-9 rounded-full border flex items-center justify-center transition-colors
                                           {{ $esFav ? 'border-red-200 bg-red-50 text-red-500 hover:bg-red-100' : 'border-gray-200 text-gray-400 hover:border-red-200 hover:text-red-500 hover:bg-red-50' }}"
                                    title="{{ $esFav ? 'Quitar de favoritos' : 'Guardar en favoritos' }}">
                                @if ($esFav)
                                    <x-heroicon-s-heart class="w-5 h-5" />
                                @else
                                    <x-heroicon-o-heart class="w-5 h-5" />
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-9 h-9 rounded-full border border-gray-200 text-gray-400 flex items-center justify-center hover:border-red-200 hover:text-red-500 hover:bg-red-50 transition-colors"
                           title="Inicia sesión para guardar en favoritos">
                            <x-heroicon-o-heart class="w-5 h-5" />
                        </a>
                    @endauth
                </div>
            </div>

            {{-- ===== FICHA TÉCNICA: grid 2 columnas ===== --}}
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Ficha técnica</h3>
            <div class="grid grid-cols-2 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden mb-6">
                @php
                    $specs = array_values(array_filter([
                        ['Categoría',   $vehicle->category->name],
                        ['Combustible', ucfirst($vehicle->fuel_type)],
                        ['Transmisión', ucfirst($vehicle->transmission_type)],
                        ['Pasajeros',   $vehicle->passenger_capacity . ' personas'],
                        $vehicle->year                  ? ['Año',     $vehicle->year]                              : null,
                        $vehicle->luggage_capacity !== null ? ['Maletas', $vehicle->luggage_capacity . ' maletas'] : null,
                        $vehicle->current_mileage !== null  ? ['Km actual', number_format($vehicle->current_mileage) . ' km'] : null,
                    ]));
                @endphp
                @foreach ($specs as $spec)
                    <div class="bg-white px-4 py-3">
                        <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $spec[0] }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $spec[1] }}</p>
                    </div>
                @endforeach
                @if (count($specs) % 2 !== 0)
                    <div class="bg-white"></div>
                @endif
            </div>

            {{-- Características --}}
            @if ($vehicle->key_features)
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Características</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach (explode(',', $vehicle->key_features) as $feature)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            {{ trim($feature) }}
                        </span>
                    @endforeach
                </div>
            @endif

            {{-- ===== EXTRAS: acordeón ===== --}}
            @if (isset($extras) && $extras->isNotEmpty())
                <div class="mb-6">
                    <x-accordion>
                        <x-accordion.item>
                            <x-accordion.title size="sm">Extras disponibles</x-accordion.title>
                            <x-accordion.content>
                                <div class="border border-gray-200 rounded-lg overflow-hidden mt-2">
                                    <table class="w-full text-sm">
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($extras as $extra)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $extra->name }}</td>
                                                    <td class="px-4 py-3 text-gray-400 text-xs">
                                                        {{ $extra->selection_type === 'single' ? 'Por alquiler' : 'Por unidad' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                                        + ${{ number_format($extra->price, 2) }}
                                                        <span class="text-xs font-normal text-gray-400"> / día</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 px-1">Los extras se seleccionan al hacer la reserva.</p>
                            </x-accordion.content>
                        </x-accordion.item>
                    </x-accordion>
                </div>
            @endif

            <hr class="border-gray-200 my-5">

            {{-- Botón reservar --}}
            <div class="flex justify-end">
                @if ($vehicle->status === 'disponible')
                    <a href="{{ auth()->check() ? route('vehiculos.reservar', $vehicle) : route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[var(--primary)] text-[var(--primary-foreground)] rounded-[var(--radius)] text-sm font-medium hover:opacity-90 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @auth Reservar este vehículo @else Iniciar sesión para reservar @endauth
                    </a>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-[var(--radius)] text-sm bg-gray-100 text-gray-500">
                        No disponible para reservas
                    </span>
                @endif
            </div>

        </div>
    </div>

</div>

</x-app-layout>
