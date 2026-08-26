<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Vehículos
            </h2>
            <a href="{{ route('admin.vehiculos.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                + Nuevo Vehículo
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio/día</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td class="px-4 py-4 font-mono text-sm text-gray-900">{{ $vehicle->plate }}</td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                    @if ($vehicle->model_alternative)
                                        <div class="text-xs text-gray-500">{{ $vehicle->model_alternative }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ $vehicle->category->name }}</td>
                                <td class="px-4 py-4 text-gray-600">$ {{ number_format($vehicle->price_per_day, 2) }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusColors = [
                                            'disponible'   => 'bg-green-100 text-green-800',
                                            'alquilado'    => 'bg-blue-100 text-blue-800',
                                            'mantenimiento'=> 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $color = $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                        {{ ucfirst($vehicle->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.vehiculos.edit', $vehicle) }}"
                                       class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</a>

                                    <form action="{{ route('admin.vehiculos.destroy', $vehicle) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar el vehículo {{ $vehicle->plate }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No hay vehículos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $vehicles->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
