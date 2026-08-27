<x-app-layout>
    <x-slot:title>Reportes | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        <h1 class="text-xl font-semibold text-[var(--foreground)]">Reportes y Estadísticas</h1>

        {{-- Vehículos más alquilados --}}
        <section>
            <h2 class="text-sm font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Vehículos más alquilados</h2>
            <div class="overflow-x-auto">
            <x-table>
                <x-table-header>
                    <x-table-row>
                        <x-table-head>#</x-table-head>
                        <x-table-head>Vehículo</x-table-head>
                        <x-table-head>Placa</x-table-head>
                        <x-table-head align="end">Reservas</x-table-head>
                    </x-table-row>
                </x-table-header>
                <x-table-body>
                    @forelse ($masAlquilados as $i => $v)
                        <x-table-row>
                            <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                                {{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}
                            </x-table-head>
                            <x-table-cell class="font-medium">{{ $v->brand }} {{ $v->model }}</x-table-cell>
                            <x-table-cell class="font-mono text-xs text-[var(--muted-foreground)] whitespace-nowrap">{{ $v->plate }}</x-table-cell>
                            <x-table-cell align="end" class="font-semibold">{{ $v->total_reservations }}</x-table-cell>
                        </x-table-row>
                    @empty
                        <x-table-row>
                            <x-table-cell colspan="4" align="center" class="py-8 text-[var(--muted-foreground)]">Sin datos aún.</x-table-cell>
                        </x-table-row>
                    @endforelse
                </x-table-body>
            </x-table>
            </div>
        </section>

        {{-- Ingresos por mes --}}
        <section>
            <h2 class="text-sm font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Ingresos por mes (últimos 6 meses)</h2>
            <div class="overflow-x-auto">
            <x-table>
                <x-table-header>
                    <x-table-row>
                        <x-table-head>#</x-table-head>
                        <x-table-head>Mes</x-table-head>
                        <x-table-head align="end">Ingresos</x-table-head>
                    </x-table-row>
                </x-table-header>
                <x-table-body>
                    @forelse ($ingresosPorMes as $i => $fila)
                        <x-table-row>
                            <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                                {{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}
                            </x-table-head>
                            <x-table-cell>{{ $fila->mes }}</x-table-cell>
                            <x-table-cell align="end" class="font-semibold">
                                $ {{ number_format($fila->total, 2) }}
                            </x-table-cell>
                        </x-table-row>
                    @empty
                        <x-table-row>
                            <x-table-cell colspan="3" align="center" class="py-8 text-[var(--muted-foreground)]">Sin ingresos registrados.</x-table-cell>
                        </x-table-row>
                    @endforelse
                </x-table-body>
            </x-table>
            </div>
        </section>

        {{-- Ocupación por categoría --}}
        <section>
            <h2 class="text-sm font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Ocupación por categoría</h2>
            <div class="overflow-x-auto">
            <x-table>
                <x-table-header>
                    <x-table-row>
                        <x-table-head>#</x-table-head>
                        <x-table-head>Categoría</x-table-head>
                        <x-table-head>Total</x-table-head>
                        <x-table-head>Alquilados</x-table-head>
                        <x-table-head align="end">Ocupación</x-table-head>
                    </x-table-row>
                </x-table-header>
                <x-table-body>
                    @forelse ($porCategoria as $i => $cat)
                        @php $pct = $cat->total > 0 ? round($cat->alquilados / $cat->total * 100) : 0; @endphp
                        <x-table-row>
                            <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                                {{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}
                            </x-table-head>
                            <x-table-cell class="font-medium">{{ $cat->name }}</x-table-cell>
                            <x-table-cell class="text-[var(--muted-foreground)]">{{ $cat->total }}</x-table-cell>
                            <x-table-cell class="text-[var(--muted-foreground)]">{{ $cat->alquilados }}</x-table-cell>
                            <x-table-cell align="end">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-16 sm:w-20 bg-[var(--muted)] rounded-full h-1.5 shrink-0">
                                        <div class="bg-[var(--primary)] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium w-8 text-right">{{ $pct }}%</span>
                                </div>
                            </x-table-cell>
                        </x-table-row>
                    @empty
                        <x-table-row>
                            <x-table-cell colspan="5" align="center" class="py-8 text-[var(--muted-foreground)]">Sin datos.</x-table-cell>
                        </x-table-row>
                    @endforelse
                </x-table-body>
            </x-table>
            </div>
        </section>

    </div>
</x-app-layout>
