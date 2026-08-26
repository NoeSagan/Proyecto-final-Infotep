<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Favoritos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($vehicles->isEmpty())
                <div class="bg-white shadow-sm rounded-lg py-16 text-center text-gray-500">
                    <p class="text-lg font-medium">No tienes vehículos favoritos</p>
                    <a href="{{ route('vehiculos.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
                        Explorar catálogo
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vehicles as $vehicle)
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4">
                                <p class="text-white font-bold text-lg">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                @if ($vehicle->model_alternative)
                                    <p class="text-blue-200 text-sm">{{ $vehicle->model_alternative }}</p>
                                @endif
                            </div>

                            <div class="p-5">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mb-3">
                                    {{ $vehicle->category->name }}
                                </span>

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
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-gray-900">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                                        <span class="text-sm text-gray-500"> / día</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('vehiculos.show', $vehicle) }}"
                                           class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-lg transition">
                                            Ver
                                        </a>
                                        <form action="{{ route('favoritos.destroy', $vehicle) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm border border-red-300 text-red-600 hover:bg-red-50 font-medium py-1.5 px-3 rounded-lg transition">
                                                Quitar
                                            </button>
                                        </form>
                                    </div>
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
