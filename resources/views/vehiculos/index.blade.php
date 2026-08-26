<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catálogo de Vehículos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Formulario de filtros --}}
            <div class="bg-white shadow-sm rounded-lg p-5 mb-6">
                <form method="GET" action="{{ route('vehiculos.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                        <input type="date" id="start_date" name="start_date"
                               value="{{ request('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de fin</label>
                        <input type="date" id="end_date" name="end_date"
                               value="{{ request('end_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="passengers" class="block text-sm font-medium text-gray-700 mb-1">Pasajeros</label>
                        <input type="number" id="passengers" name="passengers"
                               value="{{ request('passengers') }}"
                               min="1" placeholder="Cantidad"
                               class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select id="category_id" name="category_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-lg text-sm transition">
                            Buscar
                        </button>
                        @if (request()->hasAny(['start_date', 'end_date', 'passengers', 'category_id']))
                            <a href="{{ route('vehiculos.index') }}"
                               class="flex-1 text-center border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-3 rounded-lg text-sm transition">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                @if ($errors->any())
                    <div class="mt-3 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Resultados --}}
            <p class="text-sm text-gray-500 mb-4">
                {{ $vehicles->total() }} {{ $vehicles->total() === 1 ? 'vehículo encontrado' : 'vehículos encontrados' }}
                @if (request()->hasAny(['start_date', 'end_date', 'passengers', 'category_id']))
                    con los filtros aplicados
                @endif
            </p>

            @if ($vehicles->isEmpty())
                <div class="bg-white shadow-sm rounded-lg py-16 text-center text-gray-500">
                    <p class="text-lg font-medium">No hay vehículos disponibles</p>
                    <p class="text-sm mt-1">Intenta cambiar los filtros de búsqueda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vehicles as $vehicle)
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden hover:shadow-md transition">
                            {{-- Encabezado de tarjeta --}}
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4">
                                <p class="text-white font-bold text-lg">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                @if ($vehicle->model_alternative)
                                    <p class="text-blue-200 text-sm">{{ $vehicle->model_alternative }}</p>
                                @endif
                            </div>

                            <div class="p-5">
                                {{-- Categoría --}}
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mb-3">
                                    {{ $vehicle->category->name }}
                                </span>

                                {{-- Datos clave --}}
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
                                    <div>
                                        <span class="font-medium text-gray-700">Transmisión:</span><br>
                                        {{ ucfirst($vehicle->transmission_type) }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Combustible:</span><br>
                                        {{ ucfirst($vehicle->fuel_type) }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Pasajeros:</span><br>
                                        {{ $vehicle->passenger_capacity }}
                                    </div>
                                    @if ($vehicle->luggage_capacity)
                                        <div>
                                            <span class="font-medium text-gray-700">Maletas:</span><br>
                                            {{ $vehicle->luggage_capacity }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Precio y acción --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-gray-900">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                                        <span class="text-sm text-gray-500"> / día</span>
                                    </div>
                                    <a href="{{ route('vehiculos.show', $vehicle) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $vehicles->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
