<x-app-layout>
    <x-slot:title>Vehículos | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="flex items-center justify-between flex-wrap gap-3 mb-5">
            <h1 class="text-xl font-semibold text-[var(--foreground)]">Gestión de Vehículos</h1>
            <x-btn href="{{ route('admin.vehiculos.create') }}" size="sm">+ Nuevo Vehículo</x-btn>
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-4">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert style="danger" class="mb-4">{{ session('error') }}</x-alert>
        @endif

        <div class="overflow-x-auto">
        <x-table>
            <x-table-header>
                <x-table-row>
                    <x-table-head>Placa</x-table-head>
                    <x-table-head>Vehículo</x-table-head>
                    <x-table-head>Categoría</x-table-head>
                    <x-table-head>Estado</x-table-head>
                    <x-table-head align="end">Precio / día</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($vehicles as $vehicle)
                    @php
                        $statusLabel = match($vehicle->status) {
                            'disponible'    => 'Disponible',
                            'alquilado'     => 'Alquilado',
                            'mantenimiento' => 'Mantenimiento',
                            default         => ucfirst($vehicle->status),
                        };
                    @endphp
                    <x-table-row>
                        <x-table-head class="font-mono text-xs text-[var(--muted-foreground)]">
                            {{ $vehicle->plate }}
                        </x-table-head>
                        <x-table-cell>
                            <p class="font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            @if ($vehicle->model_alternative)
                                <p class="text-xs text-[var(--muted-foreground)]">{{ $vehicle->model_alternative }}</p>
                            @endif
                        </x-table-cell>
                        <x-table-cell class="text-[var(--muted-foreground)]">{{ $vehicle->category->name }}</x-table-cell>
                        <x-table-cell>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                {{ $statusLabel }}
                            </span>
                        </x-table-cell>
                        <x-table-cell align="end" class="font-semibold">
                            $ {{ number_format($vehicle->price_per_day, 2) }}
                        </x-table-cell>
                        <x-table-cell align="end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('admin.vehiculos.edit', $vehicle) }}" style="outline" size="sm">Editar</x-btn>
                                @if ($vehicle->status !== 'mantenimiento')
                                    <x-btn href="{{ route('admin.vehiculos.mantenimiento', $vehicle) }}" style="ghost" size="sm">Mant.</x-btn>
                                @endif
                                <form action="{{ route('admin.vehiculos.destroy', $vehicle) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar el vehículo {{ $vehicle->plate }}?')">
                                    @csrf @method('DELETE')
                                    <x-btn type="submit" style="danger" size="sm">Eliminar</x-btn>
                                </form>
                            </div>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="6" align="center" class="py-10 text-[var(--muted-foreground)]">
                            No hay vehículos registrados.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>
        </div>

        <div class="mt-4">{{ $vehicles->links() }}</div>

    </div>
</x-app-layout>
