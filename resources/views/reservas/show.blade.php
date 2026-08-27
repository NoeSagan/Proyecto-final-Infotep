<x-app-layout>
    <x-slot:title>Reserva #{{ $reservation->id }} | AutoAlquiler</x-slot:title>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Navegación --}}
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <a href="{{ route('mis-reservas.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Mis Reservas
            </a>
            @if (in_array($reservation->status, ['confirmada', 'completada']))
                <a href="{{ route('mis-reservas.comprobante', $reservation) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Comprobante
                </a>
            @endif
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif
        @if (session('info'))
            <x-alert class="mb-6">{{ session('info') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert style="danger" class="mb-6">{{ session('error') }}</x-alert>
        @endif

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">

            {{-- Cabecera del comprobante --}}
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
                        {{ ucfirst($reservation->status) }}
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
                <div class="flex justify-between items-center border-t border-[var(--border)] pt-5">
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

                {{-- Acciones --}}
                <div class="flex items-center justify-between gap-3 flex-wrap pt-2">
                    @if ($reservation->status === 'pendiente')
                        <x-btn href="{{ route('reservas.pago', $reservation) }}" class="gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Ir a pagar
                        </x-btn>
                    @else
                        <div></div>
                    @endif

                    @if ($reservation->canBeCancelled())
                        <button onclick="document.getElementById('modal-cancel').showModal()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-[var(--radius)] border border-[var(--border)] text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancelar reserva
                        </button>
                    @endif
                </div>

            </div>
        </div>

    </div>

    @if ($reservation->canBeCancelled())
        @php
            $fee        = $reservation->cancellationFee();
            $feePercent = $reservation->cancellationFeePercent();
        @endphp
        <dialog id="modal-cancel"
                class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] shadow-xl p-6 max-w-md w-full backdrop:bg-black/40">
            <h3 class="font-bold text-lg mb-4 text-[var(--foreground)]">¿Cancelar esta reserva?</h3>

            @if ($fee > 0)
                <div class="flex items-start gap-3 p-4 mb-4 rounded-[var(--radius)] bg-[var(--muted)] border border-[var(--border)]">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-[var(--foreground)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Cargo por cancelación tardía</p>
                        <p class="text-sm text-[var(--muted-foreground)]">
                            Menos de {{ $feePercent === 25 ? '48' : '24' }} horas de antelación: <strong>{{ $feePercent }}%</strong>
                        </p>
                    </div>
                </div>
                <div class="bg-[var(--muted)] rounded-[var(--radius)] p-4 mb-4 space-y-1 text-sm border border-[var(--border)]">
                    <div class="flex justify-between">
                        <span class="text-[var(--muted-foreground)]">Total pagado</span>
                        <span>$ {{ number_format($reservation->total_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-medium">
                        <span>Cargo cancelación ({{ $feePercent }}%)</span>
                        <span>− $ {{ number_format($fee, 2) }}</span>
                    </div>
                    <hr class="border-[var(--border)] my-2">
                    <div class="flex justify-between font-bold">
                        <span>Reembolso estimado</span>
                        <span>$ {{ number_format($reservation->total_cost - $fee, 2) }}</span>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 p-4 mb-4 rounded-[var(--radius)] bg-[var(--muted)] border border-[var(--border)]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">Cancelación <strong>gratuita</strong>. No se aplica ningún cargo.</span>
                </div>
            @endif

            <div class="flex justify-end gap-3 mt-6">
                <form method="dialog">
                    <x-btn style="outline">Volver</x-btn>
                </form>
                <form action="{{ route('mis-reservas.cancel', $reservation) }}" method="POST">
                    @csrf
                    <x-btn type="submit" style="danger">Confirmar cancelación</x-btn>
                </form>
            </div>
        </dialog>
    @endif

</x-app-layout>
