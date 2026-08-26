<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.vehiculos.index') }}" class="text-gray-500 hover:text-gray-700">&larr; Volver</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Vehículo</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form action="{{ route('admin.vehiculos.store') }}" method="POST">
                    @csrf

                    {{-- Sección: Identificación --}}
                    <h3 class="text-base font-semibold text-gray-700 mb-3 border-b pb-1">Identificación</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                           @error('category_id') border-red-500 @enderror">
                                <option value="">Seleccionar...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="plate" class="block text-sm font-medium text-gray-700 mb-1">
                                Placa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="plate" name="plate" value="{{ old('plate') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('plate') border-red-500 @enderror">
                            @error('plate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">
                                Marca <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('brand') border-red-500 @enderror">
                            @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-1">
                                Modelo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="model" name="model" value="{{ old('model') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('model') border-red-500 @enderror">
                            @error('model') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model_alternative" class="block text-sm font-medium text-gray-700 mb-1">
                                Modelo alternativo
                            </label>
                            <input type="text" id="model_alternative" name="model_alternative"
                                   value="{{ old('model_alternative') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">
                                Precio por día (USD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price_per_day" name="price_per_day"
                                   value="{{ old('price_per_day') }}" min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('price_per_day') border-red-500 @enderror">
                            @error('price_per_day') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Sección: Ficha técnica --}}
                    <h3 class="text-base font-semibold text-gray-700 mb-3 border-b pb-1">Ficha técnica</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

                        <div>
                            <label for="transmission_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Transmisión <span class="text-red-500">*</span>
                            </label>
                            <select id="transmission_type" name="transmission_type"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                           @error('transmission_type') border-red-500 @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="automatica" {{ old('transmission_type') === 'automatica' ? 'selected' : '' }}>Automática</option>
                                <option value="manual"     {{ old('transmission_type') === 'manual'     ? 'selected' : '' }}>Manual</option>
                            </select>
                            @error('transmission_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Combustible <span class="text-red-500">*</span>
                            </label>
                            <select id="fuel_type" name="fuel_type"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                           @error('fuel_type') border-red-500 @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="gasolina" {{ old('fuel_type') === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel"   {{ old('fuel_type') === 'diesel'   ? 'selected' : '' }}>Diésel</option>
                                <option value="hibrido"  {{ old('fuel_type') === 'hibrido'  ? 'selected' : '' }}>Híbrido</option>
                                <option value="electrico"{{ old('fuel_type') === 'electrico'? 'selected' : '' }}>Eléctrico</option>
                            </select>
                            @error('fuel_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="passenger_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                                Capacidad de pasajeros <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="passenger_capacity" name="passenger_capacity"
                                   value="{{ old('passenger_capacity') }}" min="1"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('passenger_capacity') border-red-500 @enderror">
                            @error('passenger_capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="luggage_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                                Capacidad de maletas
                            </label>
                            <input type="number" id="luggage_capacity" name="luggage_capacity"
                                   value="{{ old('luggage_capacity') }}" min="0"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="key_features" class="block text-sm font-medium text-gray-700 mb-1">
                                Prestaciones clave
                            </label>
                            <textarea id="key_features" name="key_features" rows="2"
                                      placeholder="Ej: Aire acondicionado, Bluetooth, Cámara de reversa"
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('key_features') }}</textarea>
                        </div>
                    </div>

                    {{-- Sección: Estado actual --}}
                    <h3 class="text-base font-semibold text-gray-700 mb-3 border-b pb-1">Estado actual</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                           @error('status') border-red-500 @enderror">
                                <option value="disponible"    {{ old('status', 'disponible') === 'disponible'    ? 'selected' : '' }}>Disponible</option>
                                <option value="alquilado"     {{ old('status') === 'alquilado'                   ? 'selected' : '' }}>Alquilado</option>
                                <option value="mantenimiento" {{ old('status') === 'mantenimiento'               ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_mileage" class="block text-sm font-medium text-gray-700 mb-1">
                                Kilometraje actual <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="current_mileage" name="current_mileage"
                                   value="{{ old('current_mileage', 0) }}" min="0"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('current_mileage') border-red-500 @enderror">
                            @error('current_mileage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_fuel_level" class="block text-sm font-medium text-gray-700 mb-1">
                                Nivel de combustible (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="current_fuel_level" name="current_fuel_level"
                                   value="{{ old('current_fuel_level', 100) }}" min="0" max="100"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                          @error('current_fuel_level') border-red-500 @enderror">
                            @error('current_fuel_level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.vehiculos.index') }}"
                           class="py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            Guardar Vehículo
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
