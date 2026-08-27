<x-app-layout>
    <x-slot:title>Editar Vehículo {{ $vehicle->plate }} | AutoAlquiler</x-slot:title>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.vehiculos.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a vehículos
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">Editar Vehículo: {{ $vehicle->plate }}</h1>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6">

            {{-- Imagen actual --}}
            @if ($vehicle->image_url)
                <div class="mb-6">
                    <p class="block text-sm font-semibold mb-1">Imagen actual</p>
                    <div class="h-40 bg-[var(--muted)] rounded-[var(--radius)] overflow-hidden">
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                             class="w-full h-full object-contain"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                    <p class="text-xs text-[var(--muted-foreground)] mt-1">Se actualiza automáticamente si cambia la marca o el modelo.</p>
                </div>
            @endif

            @if (!empty($makes))
                <datalist id="makes-list">
                    @foreach ($makes as $make)
                        <option value="{{ $make }}">
                    @endforeach
                </datalist>
            @endif

            <form action="{{ route('admin.vehiculos.update', $vehicle) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Identificación --}}
                <div>
                    <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Identificación</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label for="category_id" class="block text-sm font-semibold mb-1">Categoría</label>
                            <select id="category_id" name="category_id" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('category_id') border-[var(--danger)] @enderror">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $vehicle->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="plate" class="block text-sm font-semibold mb-1">Placa</label>
                            <input type="text" id="plate" name="plate" value="{{ old('plate', $vehicle->plate) }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('plate') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: A123456">
                            @error('plate') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-semibold mb-1">Marca</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                                   list="{{ !empty($makes) ? 'makes-list' : '' }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('brand') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: Toyota, Honda, BMW">
                            @error('brand') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-semibold mb-1">Modelo</label>
                            <input type="text" id="model" name="model" value="{{ old('model', $vehicle->model) }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('model') border-[var(--danger)] @enderror"
                                   required placeholder="Ej: Corolla, Civic, X5">
                            @error('model') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model_alternative" class="block text-sm font-semibold mb-1">
                                Versión / Trim <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <input type="text" id="model_alternative" name="model_alternative"
                                   value="{{ old('model_alternative', $vehicle->model_alternative) }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                                   placeholder="Ej: LX, Sport, Premium">
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-semibold mb-1">Año</label>
                            <input type="number" id="year" name="year" value="{{ old('year', $vehicle->year) }}"
                                   min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                        </div>

                        <div>
                            <label for="price_per_day" class="block text-sm font-semibold mb-1">Precio por día (USD)</label>
                            <input type="number" id="price_per_day" name="price_per_day"
                                   value="{{ old('price_per_day', $vehicle->price_per_day) }}" min="0" step="0.01"
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
                                <option value="automatica" {{ old('transmission_type', $vehicle->transmission_type) === 'automatica' ? 'selected' : '' }}>Automática</option>
                                <option value="manual"     {{ old('transmission_type', $vehicle->transmission_type) === 'manual'     ? 'selected' : '' }}>Manual</option>
                            </select>
                            @error('transmission_type') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-semibold mb-1">Combustible</label>
                            <select id="fuel_type" name="fuel_type" required
                                    class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('fuel_type') border-[var(--danger)] @enderror">
                                <option value="gasolina" {{ old('fuel_type', $vehicle->fuel_type) === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel"   {{ old('fuel_type', $vehicle->fuel_type) === 'diesel'   ? 'selected' : '' }}>Diésel</option>
                                <option value="hibrido"  {{ old('fuel_type', $vehicle->fuel_type) === 'hibrido'  ? 'selected' : '' }}>Híbrido</option>
                                <option value="electrico"{{ old('fuel_type', $vehicle->fuel_type) === 'electrico'? 'selected' : '' }}>Eléctrico</option>
                            </select>
                            @error('fuel_type') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="passenger_capacity" class="block text-sm font-semibold mb-1">Capacidad de pasajeros</label>
                            <input type="number" id="passenger_capacity" name="passenger_capacity"
                                   value="{{ old('passenger_capacity', $vehicle->passenger_capacity) }}" min="1" max="20"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('passenger_capacity') border-[var(--danger)] @enderror"
                                   required placeholder="5">
                            @error('passenger_capacity') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="luggage_capacity" class="block text-sm font-semibold mb-1">
                                Capacidad de maletas <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <input type="number" id="luggage_capacity" name="luggage_capacity"
                                   value="{{ old('luggage_capacity', $vehicle->luggage_capacity) }}" min="0"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                                   placeholder="2">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="key_features" class="block text-sm font-semibold mb-1">
                                Prestaciones clave <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                            </label>
                            <textarea id="key_features" name="key_features" rows="2"
                                      class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 py-2 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">{{ old('key_features', $vehicle->key_features) }}</textarea>
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
                                <option value="disponible"    {{ old('status', $vehicle->status) === 'disponible'    ? 'selected' : '' }}>Disponible</option>
                                <option value="alquilado"     {{ old('status', $vehicle->status) === 'alquilado'     ? 'selected' : '' }}>Alquilado</option>
                                <option value="mantenimiento" {{ old('status', $vehicle->status) === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                            @error('status') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_mileage" class="block text-sm font-semibold mb-1">Kilometraje actual</label>
                            <input type="number" id="current_mileage" name="current_mileage"
                                   value="{{ old('current_mileage', $vehicle->current_mileage) }}" min="0"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('current_mileage') border-[var(--danger)] @enderror"
                                   required placeholder="0">
                            @error('current_mileage') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_fuel_level" class="block text-sm font-semibold mb-1">Combustible (%)</label>
                            <input type="number" id="current_fuel_level" name="current_fuel_level"
                                   value="{{ old('current_fuel_level', $vehicle->current_fuel_level) }}" min="0" max="100"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('current_fuel_level') border-[var(--danger)] @enderror"
                                   required placeholder="100">
                            @error('current_fuel_level') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-[var(--border)] pt-5">
                    <x-btn href="{{ route('admin.vehiculos.index') }}" style="outline" size="sm">Cancelar</x-btn>
                    <x-btn type="submit" size="sm">Actualizar vehículo</x-btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
