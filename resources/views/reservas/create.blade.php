<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('vehiculos.show', $vehicle) }}" class="text-gray-500 hover:text-gray-700">&larr; Volver</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reservar — {{ $vehicle->brand }} {{ $vehicle->model }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Formulario --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <form action="{{ route('vehiculos.reservar.store', $vehicle) }}" method="POST" id="reserva-form">
                            @csrf

                            <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Fechas y pasajeros</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha de inicio <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="start_date" name="start_date"
                                           value="{{ old('start_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                                  @error('start_date') border-red-500 @enderror">
                                    @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha de fin <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="end_date" name="end_date"
                                           value="{{ old('end_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                                  @error('end_date') border-red-500 @enderror">
                                    @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="passenger_count" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cantidad de pasajeros <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal">(máx. {{ $vehicle->passenger_capacity }})</span>
                                </label>
                                <input type="number" id="passenger_count" name="passenger_count"
                                       value="{{ old('passenger_count', 1) }}"
                                       min="1" max="{{ $vehicle->passenger_capacity }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                              @error('passenger_count') border-red-500 @enderror">
                                @error('passenger_count') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @if ($extras->isNotEmpty())
                                <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Extras opcionales</h3>
                                <div class="space-y-3 mb-6">
                                    @foreach ($extras as $extra)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $extra->name }}</p>
                                                <p class="text-sm text-gray-500">$ {{ number_format($extra->price, 2) }} c/u</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <label for="extra-{{ $extra->id }}" class="text-sm text-gray-600">Cant:</label>
                                                <input type="number"
                                                       id="extra-{{ $extra->id }}"
                                                       name="extras[{{ $extra->id }}]"
                                                       value="{{ old('extras.' . $extra->id, 0) }}"
                                                       min="0" max="10"
                                                       class="w-16 border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 extra-qty"
                                                       data-price="{{ $extra->price }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                    Continuar al pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Resumen del vehículo --}}
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm rounded-lg p-5 sticky top-4">
                        <h3 class="text-base font-semibold text-gray-700 mb-3">Resumen</h3>

                        <p class="font-bold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                        <p class="text-sm text-gray-500 mb-3">{{ $vehicle->category->name }}</p>

                        <div class="text-sm text-gray-600 space-y-1 mb-4">
                            <div class="flex justify-between">
                                <span>Precio por día</span>
                                <span class="font-medium">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-3">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Días seleccionados</span>
                                <span id="resumen-dias">—</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Extras</span>
                                <span id="resumen-extras">$ 0.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-900 mt-2 pt-2 border-t">
                                <span>Total estimado</span>
                                <span id="resumen-total">—</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const pricePerDay = {{ $vehicle->price_per_day }};

        function calcular() {
            const start = document.getElementById('start_date').value;
            const end   = document.getElementById('end_date').value;

            let dias = 0;
            if (start && end && end > start) {
                dias = Math.round((new Date(end) - new Date(start)) / 86400000);
            }

            let extrasCost = 0;
            document.querySelectorAll('.extra-qty').forEach(input => {
                const qty   = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price) || 0;
                extrasCost += qty * price;
            });

            document.getElementById('resumen-dias').textContent  = dias > 0 ? dias : '—';
            document.getElementById('resumen-extras').textContent = '$ ' + extrasCost.toFixed(2);
            document.getElementById('resumen-total').textContent  =
                dias > 0 ? '$ ' + (dias * pricePerDay + extrasCost).toFixed(2) : '—';
        }

        document.getElementById('start_date').addEventListener('change', calcular);
        document.getElementById('end_date').addEventListener('change', calcular);
        document.querySelectorAll('.extra-qty').forEach(i => i.addEventListener('input', calcular));
        calcular();
    </script>
</x-app-layout>
