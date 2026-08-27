<x-app-layout>
    <x-slot:title>Dashboard | AutoAlquiler</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <h1 class="text-xl font-semibold text-[var(--foreground)]">Dashboard</h1>

        {{-- Métricas --}}
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--border)]">
                <h3 class="font-semibold text-[var(--foreground)]">Resumen</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-[var(--border)]">
                <div class="p-5">
                    <p class="text-xs text-[var(--muted-foreground)] font-medium uppercase mb-2">Confirmadas</p>
                    <p class="text-3xl font-bold text-[var(--foreground)]">{{ $reservasActivas }}</p>
                    <a href="{{ route('admin.reservas.index', ['status' => 'confirmada']) }}"
                       class="text-xs text-[var(--muted-foreground)] hover:underline mt-2 block">Ver confirmadas</a>
                </div>
                <div class="p-5">
                    <p class="text-xs text-[var(--muted-foreground)] font-medium uppercase mb-2">Pendientes</p>
                    <p class="text-3xl font-bold text-[var(--foreground)]">{{ $pendientes }}</p>
                    <a href="{{ route('admin.reservas.index', ['status' => 'pendiente']) }}"
                       class="text-xs text-[var(--muted-foreground)] hover:underline mt-2 block">Ver pendientes</a>
                </div>
                <div class="p-5">
                    <p class="text-xs text-[var(--muted-foreground)] font-medium uppercase mb-2">Disponibles</p>
                    <p class="text-3xl font-bold text-[var(--foreground)]">{{ $vehiculosDisponibles }}</p>
                    <a href="{{ route('admin.vehiculos.index') }}"
                       class="text-xs text-[var(--muted-foreground)] hover:underline mt-2 block">Ver inventario</a>
                </div>
                <div class="p-5">
                    <p class="text-xs text-[var(--muted-foreground)] font-medium uppercase mb-2">Ganancias</p>
                    <p class="text-2xl font-bold text-[var(--foreground)]">$ {{ number_format($ganancias, 2) }}</p>
                    <a href="{{ route('admin.reportes.index') }}"
                       class="text-xs text-[var(--muted-foreground)] hover:underline mt-2 block">Ver reportes</a>
                </div>
            </div>
        </div>

        {{-- Referencia de mercado --}}
        @if ($marketMakesCount !== null)
            <x-alert style="info">
                <strong>Referencia de mercado:</strong> {{ $marketMakesCount }} marcas activas en el mercado internacional.
            </x-alert>
        @endif

        {{-- Accesos rápidos --}}
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--border)]">
                <h3 class="font-semibold text-[var(--foreground)]">Accesos rápidos</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ([
                        ['route' => 'admin.categorias.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'label' => 'Categorías'],
                        ['route' => 'admin.vehiculos.index', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10l2-2zM13 6h3l3 5v5h-2', 'label' => 'Vehículos'],
                        ['route' => 'admin.extras.index', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', 'label' => 'Extras'],
                        ['route' => 'admin.usuarios.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Usuarios'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex flex-col items-center text-center p-5 bg-[var(--muted)] border border-blue-300 rounded-[var(--radius)] hover:shadow-md transition-shadow">
                            <svg class="w-7 h-7 text-[var(--muted-foreground)] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                            </svg>
                            <p class="text-xs text-[var(--muted-foreground)]">Gestión</p>
                            <p class="font-semibold text-sm text-[var(--foreground)]">{{ $item['label'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Últimas reservas --}}
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--border)]">
                <h3 class="font-semibold text-[var(--foreground)]">Últimas reservas</h3>
                <x-btn href="{{ route('admin.reservas.index') }}" style="outline" size="sm">Ver todas</x-btn>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[var(--muted)] border-b border-[var(--border)]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider">Vehículo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @forelse ($ultimasReservas as $r)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.reservas.show', $r) }}"
                                       class="font-mono text-xs font-medium text-[var(--foreground)] hover:underline">
                                        #{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-[var(--foreground)]">{{ $r->user->name }}</td>
                                <td class="px-4 py-3 text-[var(--muted-foreground)]">{{ $r->vehicle->brand }} {{ $r->vehicle->model }}</td>
                                <td class="px-4 py-3 font-medium text-[var(--foreground)]">$ {{ number_format($r->total_cost, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[var(--muted-foreground)]">No hay reservas aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
