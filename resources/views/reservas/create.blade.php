<x-app-layout>
    <x-slot:title>Reservar {{ $vehicle->brand }} {{ $vehicle->model }} | AutoAlquiler</x-slot:title>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Volver --}}
        <a href="{{ route('vehiculos.show', $vehicle) }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al vehículo
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">
            Reservar: {{ $vehicle->brand }} {{ $vehicle->model }}
        </h1>

        @if (session('error'))
            <x-alert style="danger" class="mb-6">{{ session('error') }}</x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== FORMULARIO ===== --}}
            <div class="lg:col-span-2">
                <x-card>
                    <x-card.body>
                        <form action="{{ route('vehiculos.reservar.store', $vehicle) }}" method="POST" id="reserva-form" class="space-y-5">
                            @csrf

                            {{-- Fechas --}}
                            <div class="border-t border-[var(--border)] pt-4">
                                <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Fechas y pasajeros</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium mb-1">Fecha de inicio</label>
                                    <input type="date" name="start_date" id="start_date"
                                           value="{{ old('start_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('start_date') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                                           required>
                                    @error('start_date') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium mb-1">Fecha de fin</label>
                                    <input type="date" name="end_date" id="end_date"
                                           value="{{ old('end_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('end_date') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                                           required>
                                    @error('end_date') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="passenger_count" class="block text-sm font-medium">Cantidad de pasajeros</label>
                                    <span class="text-xs text-[var(--muted-foreground)]">máx. {{ $vehicle->passenger_capacity }}</span>
                                </div>
                                <input type="number" name="passenger_count" id="passenger_count"
                                       value="{{ old('passenger_count', 1) }}"
                                       min="1" max="{{ $vehicle->passenger_capacity }}"
                                       class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('passenger_count') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                                       required>
                                @error('passenger_count') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Extras --}}
                            @if ($extras->isNotEmpty())
                                <div class="border-t border-[var(--border)] pt-4">
                                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Extras opcionales</p>
                                </div>

                                <div class="space-y-2">
                                    @foreach ($extras as $extra)
                                        <div class="flex items-center justify-between px-4 py-3 rounded-[var(--radius)] bg-[var(--muted)] border border-[var(--border)] hover:border-[var(--ring)] transition-colors">
                                            <div>
                                                <p class="font-medium text-sm">{{ $extra->name }}</p>
                                                <p class="text-xs text-[var(--muted-foreground)]">
                                                    ${{ number_format($extra->price, 2) }}
                                                    {{ $extra->selection_type === 'single' ? 'por alquiler' : 'c/u' }}
                                                </p>
                                            </div>

                                            @if ($extra->selection_type === 'single')
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox"
                                                           name="extras[{{ $extra->id }}]"
                                                           value="1"
                                                           {{ old('extras.'.$extra->id) ? 'checked' : '' }}
                                                           class="w-4 h-4 rounded border-[var(--border)] accent-[var(--primary)] focus:ring-[var(--ring)] extra-check"
                                                           data-price="{{ $extra->price }}">
                                                    <span class="text-sm">Agregar</span>
                                                </label>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-[var(--muted-foreground)]">Cant:</span>
                                                    <input type="number"
                                                           name="extras[{{ $extra->id }}]"
                                                           value="{{ old('extras.'.$extra->id, 0) }}"
                                                           min="0" max="10"
                                                           class="w-16 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-2 h-8 text-sm text-center focus:outline-none focus:ring-2 focus:ring-[var(--ring)] extra-qty"
                                                           data-price="{{ $extra->price }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex justify-end pt-2">
                                <x-btn type="submit" class="gap-2">
                                    Continuar al pago
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </x-btn>
                            </div>
                        </form>
                    </x-card.body>
                </x-card>
            </div>

            {{-- ===== RESUMEN ===== --}}
            <div class="lg:col-span-1">
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-5 sticky top-24">

                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Resumen</p>

                    <p class="font-bold text-[var(--foreground)]">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                    <p class="text-sm text-[var(--muted-foreground)] mb-4">{{ $vehicle->category->name }}</p>

                    <div class="border-t border-[var(--border)] pt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[var(--muted-foreground)]">Precio / día</span>
                            <span class="font-medium">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[var(--muted-foreground)]">Días</span>
                            <span id="resumen-dias" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[var(--muted-foreground)]">Extras</span>
                            <span id="resumen-extras" class="font-medium">$ 0.00</span>
                        </div>
                    </div>

                    <div class="border-t border-[var(--border)] mt-4 pt-4 flex justify-between items-baseline">
                        <span class="font-semibold text-sm">Total estimado</span>
                        <span id="resumen-total" class="text-xl font-bold text-[var(--foreground)]">$ 0.00</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const pricePerDay = {{ $vehicle->price_per_day }};

        function calcular() {
            const start = document.querySelector('[name=start_date]').value;
            const end   = document.querySelector('[name=end_date]').value;

            let dias = 0;
            if (start && end && end > start)
                dias = Math.round((new Date(end) - new Date(start)) / 86400000);

            let extrasCost = 0;
            document.querySelectorAll('.extra-qty').forEach(i => {
                extrasCost += (parseInt(i.value) || 0) * (parseFloat(i.dataset.price) || 0);
            });
            document.querySelectorAll('.extra-check').forEach(i => {
                if (i.checked) extrasCost += parseFloat(i.dataset.price) || 0;
            });

            document.getElementById('resumen-dias').textContent   = dias > 0 ? dias : '0';
            document.getElementById('resumen-extras').textContent = '$ ' + extrasCost.toFixed(2);
            document.getElementById('resumen-total').textContent  = dias > 0
                ? '$ ' + (dias * pricePerDay + extrasCost).toFixed(2) : '$ 0.00';
        }

        document.querySelector('[name=start_date]').addEventListener('change', calcular);
        document.querySelector('[name=end_date]').addEventListener('change', calcular);
        document.querySelectorAll('.extra-qty').forEach(i  => i.addEventListener('input', calcular));
        document.querySelectorAll('.extra-check').forEach(i => i.addEventListener('change', calcular));
        calcular();
    </script>
</x-app-layout>
