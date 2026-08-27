<x-public-layout>
    <x-slot:title>Catálogo de Vehículos | AutoAlquiler</x-slot:title>

{{-- ===== BÚSQUEDA Y FILTROS ===== --}}
<div class="bg-[var(--card)] border-b border-[var(--border)] shadow-sm relative z-10">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form method="GET" action="{{ route('vehiculos.index') }}">

            {{-- Fila 1: búsqueda + fechas + pasajeros + botón --}}
            <div class="flex flex-col lg:flex-row gap-2 mb-2.5">

                {{-- Buscador --}}
                <div class="relative flex-1 min-w-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 opacity-35 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Marca, modelo..."
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] pl-9 pr-3 h-11 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                </div>

                {{-- Rango de fechas unido --}}
                <div class="flex border border-[var(--border)] rounded-[var(--radius)] overflow-hidden bg-[var(--input)] h-11 divide-x divide-[var(--border)]">
                    <div class="flex flex-col justify-center px-3 py-1 min-w-[130px]">
                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-40 leading-none mb-0.5">Desde</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="border-none bg-transparent text-sm h-5 focus:outline-none w-full p-0">
                    </div>
                    <div class="flex flex-col justify-center px-3 py-1 min-w-[130px]">
                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-40 leading-none mb-0.5">Hasta</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="border-none bg-transparent text-sm h-5 focus:outline-none w-full p-0">
                    </div>
                </div>

                {{-- Pasajeros --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 opacity-35 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input type="number" name="passengers" value="{{ request('passengers') }}"
                           min="1" placeholder="Pasajeros"
                           class="w-36 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] pl-9 pr-3 h-11 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                </div>

                {{-- Botón --}}
                <button type="submit"
                        class="h-11 px-6 bg-[var(--primary)] text-[var(--primary-foreground)] rounded-[var(--radius)] text-sm font-semibold hover:opacity-90 transition-opacity inline-flex items-center justify-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    Buscar
                </button>
            </div>

            {{-- Fila 2: filtros secundarios --}}
            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-end">

                {{-- Categoría + Combustible agrupados --}}
                <div class="flex-1 min-w-0 w-full">
                    <label class="block text-[10px] font-bold uppercase tracking-wider opacity-40 mb-1">Categoría y combustible</label>
                    <div class="flex border border-[var(--border)] rounded-[var(--radius)] overflow-hidden bg-[var(--input)] h-9 divide-x divide-[var(--border)]">
                        <select name="category_id"
                                class="flex-1 min-w-0 border-none bg-transparent px-3 text-sm focus:outline-none">
                            <option value="">Todas las categorías</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="fuel_type"
                                class="flex-1 min-w-0 border-none bg-transparent px-3 text-sm focus:outline-none">
                            <option value="">Cualquier combustible</option>
                            <option value="gasolina"  {{ request('fuel_type') === 'gasolina'  ? 'selected' : '' }}>Gasolina</option>
                            <option value="diesel"    {{ request('fuel_type') === 'diesel'    ? 'selected' : '' }}>Diésel</option>
                            <option value="hibrido"   {{ request('fuel_type') === 'hibrido'   ? 'selected' : '' }}>Híbrido</option>
                            <option value="electrico" {{ request('fuel_type') === 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                        </select>
                    </div>
                </div>

                {{-- Transmisión --}}
                <div class="w-full sm:w-40 sm:shrink-0">
                    <label class="block text-[10px] font-bold uppercase tracking-wider opacity-40 mb-1">Transmisión</label>
                    <select name="transmission_type"
                            class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                        <option value="">Cualquiera</option>
                        <option value="automatica" {{ request('transmission_type') === 'automatica' ? 'selected' : '' }}>Automática</option>
                        <option value="manual"     {{ request('transmission_type') === 'manual'     ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                {{-- Presupuesto --}}
                <div class="w-full sm:w-40 sm:shrink-0">
                    <label class="block text-[10px] font-bold uppercase tracking-wider opacity-40 mb-1">Presupuesto / día</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs opacity-40 pointer-events-none font-semibold">$</span>
                        <input type="number" name="price_max" value="{{ request('price_max') }}"
                               min="0" placeholder="Sin límite"
                               class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] pl-6 pr-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                    </div>
                </div>

            </div>

            {{-- Chips de filtros activos --}}
            @php
                $hasFilters = request()->hasAny(['search','start_date','end_date','passengers','category_id','transmission_type','fuel_type','price_min','price_max']);
            @endphp
            @if ($hasFilters)
                <div class="mt-2.5 flex items-center gap-1.5 flex-wrap text-xs">
                    <span class="opacity-40 text-[10px] font-bold uppercase tracking-wider">Filtros:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">"{{ request('search') }}"</span>
                    @endif
                    @if(request('category_id'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">{{ $categories->firstWhere('id', request('category_id'))?->name }}</span>
                    @endif
                    @if(request('fuel_type'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">{{ ucfirst(request('fuel_type')) }}</span>
                    @endif
                    @if(request('transmission_type'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">{{ ucfirst(request('transmission_type')) }}</span>
                    @endif
                    @if(request('start_date') && request('end_date'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">
                            {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m') }} – {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m') }}
                        </span>
                    @endif
                    @if(request('passengers'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">{{ request('passengers') }}+ pas.</span>
                    @endif
                    @if(request('price_max'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[var(--muted)] border border-[var(--border)]">Máx. ${{ request('price_max') }}</span>
                    @endif
                    <a href="{{ route('vehiculos.index') }}"
                       class="inline-flex items-center px-2 py-0.5 rounded-full opacity-50 hover:opacity-100 hover:bg-[var(--muted)] border border-transparent hover:border-[var(--border)] transition-all ml-1">
                        × Limpiar
                    </a>
                </div>
            @endif

        </form>
    </div>
</div>

{{-- ===== CONTENIDO PRINCIPAL ===== --}}
<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <p class="text-sm opacity-50 mb-5">
        <span class="font-semibold opacity-100">{{ $vehicles->total() }}</span>
        {{ $vehicles->total() === 1 ? 'vehículo encontrado' : 'vehículos encontrados' }}
    </p>

    @if ($vehicles->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-full bg-[var(--muted)] flex items-center justify-center mb-4">
                <svg class="w-7 h-7 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Sin resultados</h3>
            <p class="opacity-50 mb-4 max-w-sm">Prueba con otros filtros o amplía la búsqueda.</p>
            <x-btn href="{{ route('vehiculos.index') }}">Ver todos los vehículos</x-btn>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach ($vehicles as $vehicle)
                @php
                    $isApi = $vehicle->is_api ?? false;
                    $href  = $isApi
                        ? route('vehiculos.listing', $vehicle->listing_id)
                        : route('vehiculos.show', $vehicle);
                @endphp

                <a href="{{ $href }}"
                   class="group block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-200 hover:border-gray-300">

                    {{-- Imagen --}}
                    <div class="relative bg-gray-100 h-40 overflow-hidden">
                        @if ($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}"
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">
                        @endif
                        <div data-fallback class="absolute inset-0 flex flex-col items-center justify-center {{ $vehicle->image_url ? 'hidden' : '' }}">
                            <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zm-13-4h14l1-5H5l1 5z"/>
                            </svg>
                            <span class="text-xs opacity-30 mt-2">{{ $vehicle->brand }}</span>
                        </div>

                        {{-- Pastilla categoría --}}
                        <div class="absolute top-2.5 left-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-900 text-white shadow-sm">
                                {{ $vehicle->category->name }}
                            </span>
                        </div>

                        {{-- Corazón favorito --}}
                        @if (!$isApi)
                            <div class="absolute top-2.5 right-2.5">
                                @auth
                                    @php $esFav = auth()->user()->favoriteVehicles()->where('vehicle_id', $vehicle->id)->exists(); @endphp
                                    <form action="{{ route('favoritos.toggle', $vehicle) }}" method="POST"
                                          onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit"
                                                class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm shadow-sm flex items-center justify-center hover:bg-white hover:scale-110 transition-all {{ $esFav ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                            @if ($esFav)
                                                <x-heroicon-s-heart class="w-4 h-4" />
                                            @else
                                                <x-heroicon-o-heart class="w-4 h-4" />
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <button type="button"
                                            onclick="event.stopPropagation(); window.location.href='{{ route('login') }}'"
                                            class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm text-gray-400 shadow-sm flex items-center justify-center hover:text-red-500 hover:bg-white hover:scale-110 transition-all">
                                        <x-heroicon-o-heart class="w-4 h-4" />
                                    </button>
                                @endauth
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="font-semibold text-gray-900 text-sm leading-snug">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </p>
                            {{-- Combustible: fondo más oscuro para contraste --}}
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-700 shrink-0">
                                {{ ucfirst($vehicle->fuel_type) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">
                            {{ $vehicle->year ? $vehicle->year . ' · ' : '' }}{{ ucfirst($vehicle->transmission_type) }} · {{ $vehicle->passenger_capacity }} pas.
                        </p>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <div>
                                <span class="font-bold text-gray-900">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                                <span class="text-xs text-gray-400"> / día</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium group-hover:underline">Ver detalles</span>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>

        {{-- Paginación centrada --}}
        <div class="mt-10 flex justify-center">
            {{ $vehicles->links() }}
        </div>
    @endif

</div>

</x-public-layout>
