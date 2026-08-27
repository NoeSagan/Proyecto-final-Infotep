<x-app-layout>
    <x-slot:title>Reservas | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="flex items-center justify-between flex-wrap gap-3 mb-5">
            <h1 class="text-xl font-semibold text-[var(--foreground)]">Gestión de Reservas</h1>
            <x-btn href="{{ route('admin.reservas.export', request()->only(['status', 'search'])) }}" style="outline" size="sm">
                ↓ Exportar CSV
            </x-btn>
        </div>

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center mb-5">
            <div class="flex flex-wrap gap-1.5">
                @foreach (['todos' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'completada' => 'Completada', 'cancelada' => 'Cancelada'] as $val => $label)
                    <a href="{{ route('admin.reservas.index', array_merge(request()->only(['search']), $val !== 'todos' ? ['status' => $val] : [])) }}"
                       class="inline-flex items-center px-3 h-7 text-xs rounded-[var(--radius-inner)] border transition-colors
                              {{ request('status', 'todos') === $val ? 'bg-[var(--primary)] text-[var(--primary-foreground)] border-[var(--primary)]' : 'border-[var(--border)] hover:bg-[var(--muted)]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('admin.reservas.index') }}" class="flex gap-2 w-full sm:ml-auto sm:w-auto">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por # o cliente..."
                       class="flex-1 sm:w-52 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-8 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                <x-btn type="submit" size="sm">Buscar</x-btn>
                @if (request('search'))
                    <x-btn href="{{ route('admin.reservas.index', request()->only(['status'])) }}" style="outline" size="sm">×</x-btn>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
        <x-table>
            <x-table-header>
                <x-table-row>
                    <x-table-head>#</x-table-head>
                    <x-table-head>Cliente</x-table-head>
                    <x-table-head>Vehículo</x-table-head>
                    <x-table-head>Fechas</x-table-head>
                    <x-table-head>Estado</x-table-head>
                    <x-table-head align="end">Total</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($reservations as $r)
                    @php
                        $statusLabel = match($r->status) {
                            'pendiente'  => 'Pendiente',
                            'confirmada' => 'Confirmada',
                            'completada' => 'Completada',
                            'cancelada'  => 'Cancelada',
                            default      => ucfirst($r->status),
                        };
                    @endphp
                    <x-table-row>
                        <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                            #{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}
                        </x-table-head>
                        <x-table-cell>
                            <p class="font-medium">{{ $r->user->name }}</p>
                            <p class="text-xs text-[var(--muted-foreground)]">{{ $r->user->email }}</p>
                        </x-table-cell>
                        <x-table-cell>{{ $r->vehicle->brand }} {{ $r->vehicle->model }}</x-table-cell>
                        <x-table-cell class="text-xs text-[var(--muted-foreground)] whitespace-nowrap">
                            {{ $r->start_date->format('d/m/Y') }} → {{ $r->end_date->format('d/m/Y') }}
                        </x-table-cell>
                        <x-table-cell>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                {{ $statusLabel }}
                            </span>
                        </x-table-cell>
                        <x-table-cell align="end" class="font-semibold">
                            $ {{ number_format($r->total_cost, 2) }}
                        </x-table-cell>
                        <x-table-cell align="end">
                            <x-btn href="{{ route('admin.reservas.show', $r) }}" size="sm">Ver</x-btn>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="7" align="center" class="py-10 text-[var(--muted-foreground)]">
                            No hay reservas.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>
        </div>

        <div class="mt-4">{{ $reservations->links() }}</div>

    </div>
</x-app-layout>
