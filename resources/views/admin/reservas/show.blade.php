<x-app-layout>
    <x-slot:title>Reserva #{{ $reservation->id }} | AutoAlquiler</x-slot:title>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Navegación --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.reservas.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Reservas
            </a>
            <span class="text-[var(--muted-foreground)]">/</span>
            <span class="text-sm font-medium">Reserva #{{ $reservation->id }}</span>
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">

            {{-- Cabecera --}}
            <div class="border-b border-[var(--border)] px-6 py-6">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="space-y-1.5">
                        <p class="text-xs font-medium text-[var(--muted-foreground)]">
                            {{ $reservation->user->name }} &middot; {{ $reservation->user->email }}
                        </p>
                        <h2 class="text-xl font-bold text-[var(--foreground)]">
                            {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                        </h2>
                        <p class="text-sm text-[var(--muted-foreground)]">
                            {{ $reservation->vehicle->category->name }} &middot; Placa {{ $reservation->vehicle->plate }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-900 text-white shrink-0">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- Detalle --}}
                <div>
                    <h3 class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Detalle de la reserva</h3>
                    <div class="border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-[var(--border)]">
                                <tr>
                                    <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium w-28 bg-[var(--muted)]">Inicio</td>
                                    <td class="px-4 py-2.5 font-semibold">{{ $reservation->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Fin</td>
                                    <td class="px-4 py-2.5 font-semibold">{{ $reservation->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Días</td>
                                    <td class="px-4 py-2.5 font-semibold">{{ $reservation->start_date->diffInDays($reservation->end_date) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Pasajeros</td>
                                    <td class="px-4 py-2.5 font-semibold">{{ $reservation->passenger_count }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Precio / día</td>
                                    <td class="px-4 py-2.5 font-semibold">$ {{ number_format($reservation->vehicle->price_per_day, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Extras --}}
                @if ($reservation->extras->isNotEmpty())
                    <div>
                        <h3 class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Extras</h3>
                        <div class="border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-[var(--border)]">
                                    @foreach ($reservation->extras as $extra)
                                        <tr>
                                            <td class="px-4 py-3">{{ $extra->name }} × {{ $extra->pivot->quantity }}</td>
                                            <td class="px-4 py-3 text-right font-medium">$ {{ number_format($extra->price * $extra->pivot->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Total --}}
                <div class="flex justify-between items-baseline border-t border-[var(--border)] pt-5">
                    <span class="font-bold text-[var(--foreground)]">Total</span>
                    <span class="text-2xl font-bold text-[var(--foreground)]">$ {{ number_format($reservation->total_cost, 2) }}</span>
                </div>

                {{-- Datos de entrega --}}
                @if (in_array($reservation->status, ['confirmada', 'completada']))
                    <div>
                        <h3 class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Datos de entrega</h3>
                        <div class="border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-[var(--border)]">
                                    <tr>
                                        <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium w-28 bg-[var(--muted)]">Placa</td>
                                        <td class="px-4 py-2.5 font-mono font-bold">{{ $reservation->delivery_plate }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Combustible</td>
                                        <td class="px-4 py-2.5 font-semibold">{{ $reservation->delivery_fuel_level }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-[var(--muted-foreground)] font-medium bg-[var(--muted)]">Kilometraje</td>
                                        <td class="px-4 py-2.5 font-semibold">{{ number_format($reservation->delivery_mileage) }} km</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Cambiar estado --}}
                @if (!in_array($reservation->status, ['completada', 'cancelada']))
                    <div>
                        <h3 class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Cambiar estado</h3>
                        <form action="{{ route('admin.reservas.estado', $reservation) }}" method="POST" class="flex gap-3 flex-wrap">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    class="flex-1 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                                @if ($reservation->status === 'pendiente')
                                    <option value="confirmada">Confirmada</option>
                                    <option value="cancelada">Cancelada</option>
                                @elseif ($reservation->status === 'confirmada')
                                    <option value="completada">Completada</option>
                                    <option value="cancelada">Cancelada</option>
                                @endif
                            </select>
                            <x-btn type="submit" size="sm">Actualizar estado</x-btn>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
