<x-app-layout>
    <x-slot:title>Mis Reservas | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-5">Mis Reservas</h1>

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
                    <x-table-head>#</x-table-head>
                    <x-table-head>Vehículo</x-table-head>
                    <x-table-head>Fechas</x-table-head>
                    <x-table-head>Estado</x-table-head>
                    <x-table-head align="end">Total</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($reservations as $i => $reservation)
                    @php
                        $statusLabel = match($reservation->status) {
                            'pendiente'  => 'Pendiente',
                            'confirmada' => 'Confirmada',
                            'completada' => 'Completada',
                            'cancelada'  => 'Cancelada',
                            default      => ucfirst($reservation->status),
                        };
                        $dias = $reservation->start_date->diffInDays($reservation->end_date);
                    @endphp
                    <x-table-row>
                        <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                            #{{ str_pad($reservations->firstItem() + $i, 4, '0', STR_PAD_LEFT) }}
                        </x-table-head>
                        <x-table-cell>
                            <p class="font-medium">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</p>
                            <p class="text-xs text-[var(--muted-foreground)]">{{ $reservation->vehicle->category->name }}</p>
                        </x-table-cell>
                        <x-table-cell class="text-xs text-[var(--muted-foreground)] whitespace-nowrap">
                            {{ $reservation->start_date->format('d/m/Y') }} → {{ $reservation->end_date->format('d/m/Y') }}
                            <span class="ml-1">({{ $dias }} día{{ $dias !== 1 ? 's' : '' }})</span>
                        </x-table-cell>
                        <x-table-cell>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                {{ $statusLabel }}
                            </span>
                        </x-table-cell>
                        <x-table-cell align="end" class="font-semibold">
                            $ {{ number_format($reservation->total_cost, 2) }}
                        </x-table-cell>
                        <x-table-cell align="end">
                            <div class="flex items-center justify-end gap-1">
                                @if ($reservation->status === 'pendiente')
                                    <x-btn href="{{ route('reservas.pago', $reservation) }}" size="sm">Pagar</x-btn>
                                @endif
                                <x-btn href="{{ route('mis-reservas.show', $reservation) }}" size="sm">Ver</x-btn>
                            </div>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="6" align="center" class="py-10 text-[var(--muted-foreground)]">
                            No tienes reservas todavía.
                            <a href="{{ route('vehiculos.index') }}" class="ml-2 underline text-[var(--foreground)] hover:opacity-70">Ver vehículos disponibles</a>
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>
        </div>

        @if ($reservations->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $reservations->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
