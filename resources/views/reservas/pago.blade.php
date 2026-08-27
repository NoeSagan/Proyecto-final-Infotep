<x-app-layout>
    <x-slot:title>Pago Reserva #{{ $reservation->id }} | AutoAlquiler</x-slot:title>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Volver --}}
        <a href="{{ route('mis-reservas.show', $reservation) }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a la reserva
        </a>

        {{-- Pasos del flujo --}}
        <div class="flex items-center justify-center gap-3 mb-8 text-xs text-[var(--muted-foreground)]">
            <span class="flex items-center gap-1.5">
                <span class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">1</span>
                Vehículo
            </span>
            <span class="w-8 border-t border-[var(--border)]"></span>
            <span class="flex items-center gap-1.5">
                <span class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">2</span>
                Extras
            </span>
            <span class="w-8 border-t border-[var(--border)]"></span>
            <span class="flex items-center gap-1.5 font-semibold text-[var(--foreground)]">
                <span class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">3</span>
                Pago
            </span>
        </div>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">

            {{-- Cabecera --}}
            <div class="border-b border-[var(--border)] px-6 py-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1.5">
                        <p class="text-xs font-medium text-[var(--muted-foreground)]">Reserva #{{ $reservation->id }}</p>
                        <h2 class="text-xl font-bold text-[var(--foreground)]">
                            {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                        </h2>
                        <p class="text-sm text-[var(--muted-foreground)]">{{ $reservation->vehicle->category->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-900 text-white shrink-0">
                        Pendiente
                    </span>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- Detalle de la reserva --}}
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
                        <h3 class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Extras incluidos</h3>
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
                    <span class="font-bold text-[var(--foreground)]">Total a pagar</span>
                    <span class="text-2xl font-bold text-[var(--foreground)]">$ {{ number_format($reservation->total_cost, 2) }}</span>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <form action="{{ route('reservas.pago.confirm', $reservation) }}" method="POST" class="flex-1">
                        @csrf
                        <x-btn type="submit" class="w-full gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Confirmar y pagar
                        </x-btn>
                    </form>
                    <x-btn href="{{ route('mis-reservas.index') }}" style="outline" class="flex-1 justify-center">
                        Pagar más tarde
                    </x-btn>
                </div>

                <p class="text-xs text-[var(--muted-foreground)] text-center">
                    Tu reserva está guardada como pendiente. Puedes confirmarla desde "Mis Reservas".
                </p>

            </div>
        </div>
    </div>
</x-app-layout>
