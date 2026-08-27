<x-app-layout>
    <x-slot:title>Nuevo Vehículo | AutoAlquiler</x-slot:title>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.vehiculos.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a vehículos
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">Nuevo Vehículo</h1>

        {{-- Autocompletar por VIN --}}
        <x-alert style="info" class="mb-6">
            <div class="w-full">
                <p class="font-semibold text-sm mb-1">Autocompletar desde VIN</p>
                <p class="text-xs opacity-75 mb-3">Introduce el número VIN del vehículo para rellenar automáticamente marca, modelo y tipo.</p>
                <div class="flex gap-2">
                    <input type="text" id="vin_input" placeholder="Ej: 1HGBH41JXMN109186"
                           maxlength="17"
                           class="flex-1 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-8 text-sm uppercase placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                    <x-btn type="button" id="vin_btn" onclick="lookupVin()" style="outline" size="sm">Buscar VIN</x-btn>
                </div>
                <p id="vin_status" class="text-xs mt-2 hidden"></p>
            </div>
        </x-alert>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6">

            @if (!empty($makes))
                <datalist id="makes-list">
                    @foreach ($makes as $make)
                        <option value="{{ $make }}">
                    @endforeach
                </datalist>
            @endif

            <form action="{{ route('admin.vehiculos.store') }}" method="POST" id="vehicle-form" class="space-y-6">
                @csrf

                {{-- Identificación --}}
                <div>
                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Identificación</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label for="category_id" class="block text-sm font-semibold mb-1">Categoría</label>
                            <select id="category_id" name="category_id" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('category_id') border-[var(--danger)] @enderror">
                                <option value="">Seleccionar categoría...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="plate" class="block text-sm font-semibold mb-1">Placa</label>
                            <input type="text" id="plate" name="plate" value="{{ old('plate') }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('plate') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: A123456">
                            @error('plate') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-semibold mb-1">Marca</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                                   list="{{ !empty($makes) ? 'makes-list' : '' }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('brand') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: Toyota, Honda, BMW">
                            @error('brand') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-semibold mb-1">Modelo</label>
                            <input type="text" id="model" name="model" value="{{ old('model') }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('model') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: Corolla, Civic, X5">
                            @error('model') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model_alternative" class="block text-sm font-semibold mb-1">
                                Versión / Trim <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <input type="text" id="model_alternative" name="model_alternative"
                                   value="{{ old('model_alternative') }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                                   placeholder="Ej: LX, Sport, Premium">
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-semibold mb-1">Año</label>
                            <input type="number" id="year" name="year" value="{{ old('year') }}"
                                   min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                        </div>

                        <div>
                            <label for="price_per_day" class="block text-sm font-semibold mb-1">Precio por día (USD)</label>
                            <input type="number" id="price_per_day" name="price_per_day"
                                   value="{{ old('price_per_day') }}" min="0" step="0.01"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('price_per_day') border-[var(--danger)] @enderror"
                                   required placeholder="0.00">
                            @error('price_per_day') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Ficha técnica --}}
                <div class="border-t border-[var(--border)] pt-5">
                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Ficha técnica</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label for="transmission_type" class="block text-sm font-semibold mb-1">Transmisión</label>
                            <select id="transmission_type" name="transmission_type" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('transmission_type') border-[var(--danger)] @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="automatica" {{ old('transmission_type') === 'automatica' ? 'selected' : '' }}>Automática</option>
                                <option value="manual"     {{ old('transmission_type') === 'manual'     ? 'selected' : '' }}>Manual</option>
                            </select>
                            @error('transmission_type') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-semibold mb-1">Combustible</label>
                            <select id="fuel_type" name="fuel_type" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('fuel_type') border-[var(--danger)] @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="gasolina" {{ old('fuel_type') === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel"   {{ old('fuel_type') === 'diesel'   ? 'selected' : '' }}>Diésel</option>
                                <option value="hibrido"  {{ old('fuel_type') === 'hibrido'  ? 'selected' : '' }}>Híbrido</option>
                                <option value="electrico"{{ old('fuel_type') === 'electrico'? 'selected' : '' }}>Eléctrico</option>
                            </select>
                            @error('fuel_type') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="passenger_capacity" class="block text-sm font-semibold mb-1">Capacidad de pasajeros</label>
                            <input type="number" id="passenger_capacity" name="passenger_capacity"
                                   value="{{ old('passenger_capacity') }}" min="1" max="20"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('passenger_capacity') border-[var(--danger)] @enderror"
                                   required placeholder="5">
                            @error('passenger_capacity') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="luggage_capacity" class="block text-sm font-semibold mb-1">
                                Capacidad de maletas <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <input type="number" id="luggage_capacity" name="luggage_capacity"
                                   value="{{ old('luggage_capacity') }}" min="0"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                                   placeholder="2">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="key_features" class="block text-sm font-semibold mb-1">
                                Prestaciones clave <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <textarea id="key_features" name="key_features" rows="2"
                                      placeholder="Ej: Aire acondicionado, Bluetooth, Cámara de reversa"
                                      class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 py-2 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">{{ old('key_features') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Estado actual --}}
                <div class="border-t border-[var(--border)] pt-5">
                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Estado actual</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label for="status" class="block text-sm font-semibold mb-1">Estado</label>
                            <select id="status" name="status" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('status') border-[var(--danger)] @enderror">
                                <option value="disponible"    {{ old('status', 'disponible') === 'disponible'    ? 'selected' : '' }}>Disponible</option>
                                <option value="alquilado"     {{ old('status') === 'alquilado'                   ? 'selected' : '' }}>Alquilado</option>
                                <option value="mantenimiento" {{ old('status') === 'mantenimiento'               ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                            @error('status') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_mileage" class="block text-sm font-semibold mb-1">Kilometraje actual</label>
                            <input type="number" id="current_mileage" name="current_mileage"
                                   value="{{ old('current_mileage', 0) }}" min="0"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('current_mileage') border-[var(--danger)] @enderror"
                                   required placeholder="0">
                            @error('current_mileage') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_fuel_level" class="block text-sm font-semibold mb-1">Combustible (%)</label>
                            <input type="number" id="current_fuel_level" name="current_fuel_level"
                                   value="{{ old('current_fuel_level', 100) }}" min="0" max="100"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('current_fuel_level') border-[var(--danger)] @enderror"
                                   required placeholder="100">
                            @error('current_fuel_level') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-[var(--border)] pt-5">
                    <x-btn href="{{ route('admin.vehiculos.index') }}" style="outline" size="sm">Cancelar</x-btn>
                    <x-btn type="submit" size="sm">Guardar vehículo</x-btn>
                </div>
            </form>
        </div>
    </div>

    <script>
    async function lookupVin() {
        const vin = document.getElementById('vin_input').value.trim().toUpperCase();
        const btn = document.getElementById('vin_btn');
        const status = document.getElementById('vin_status');

        if (vin.length !== 17) {
            status.textContent = 'El VIN debe tener exactamente 17 caracteres.';
            status.className = 'text-xs mt-2 text-[var(--danger)]';
            status.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Buscando...';
        status.classList.add('hidden');

        try {
            const res = await fetch(`{{ url('admin/vehiculos/vin') }}/${vin}`);
            const data = await res.json();

            if (!res.ok) throw new Error(data.error ?? 'No se encontraron datos.');

            if (data.brand)             document.getElementById('brand').value = data.brand;
            if (data.model)             document.getElementById('model').value = data.model;
            if (data.model_alternative) document.getElementById('model_alternative').value = data.model_alternative;
            if (data.transmission_type) {
                const sel = document.getElementById('transmission_type');
                for (let opt of sel.options) {
                    if (opt.value === data.transmission_type) { sel.value = data.transmission_type; break; }
                }
            }
            if (data.fuel_type) {
                const sel = document.getElementById('fuel_type');
                for (let opt of sel.options) {
                    if (opt.value === data.fuel_type) { sel.value = data.fuel_type; break; }
                }
            }

            status.textContent = 'Datos cargados correctamente desde el VIN.';
            status.className = 'text-xs mt-2 text-[var(--success)]';
        } catch (e) {
            status.textContent = e.message;
            status.className = 'text-xs mt-2 text-[var(--danger)]';
        } finally {
            status.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Buscar VIN';
        }
    }

    document.getElementById('vin_input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); lookupVin(); }
    });
    </script>
</x-app-layout>
