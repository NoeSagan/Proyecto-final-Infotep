<x-app-layout>
    <x-slot:title>Mis Favoritos | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <h1 class="text-xl font-semibold text-gray-900 mb-5">Mis Favoritos</h1>

        @if (session('success'))
            <div class="flex items-center gap-2 px-4 py-3 mb-5 rounded-[var(--radius)] bg-green-50 border border-green-200 text-green-800 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($vehicles->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-16 h-16 rounded-full bg-[var(--muted)] flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Sin favoritos aún</h3>
                <p class="opacity-50 mb-4 max-w-sm">Marca vehículos con el corazón para guardarlos aquí.</p>
                <x-btn href="{{ route('vehiculos.index') }}">Explorar catálogo</x-btn>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach ($vehicles as $vehicle)
                    <a href="{{ route('vehiculos.show', $vehicle) }}"
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

                            {{-- Corazón relleno - toggle favorito --}}
                            <div class="absolute top-2.5 right-2.5">
                                <form action="{{ route('favoritos.toggle', $vehicle) }}" method="POST"
                                      onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit"
                                            class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm text-red-500 shadow-sm flex items-center justify-center hover:bg-white hover:scale-110 transition-all">
                                        <x-heroicon-s-heart class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <p class="font-semibold text-gray-900 text-sm leading-snug">
                                    {{ $vehicle->brand }} {{ $vehicle->model }}
                                </p>
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

            <div class="mt-10 flex justify-center">
                {{ $vehicles->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
