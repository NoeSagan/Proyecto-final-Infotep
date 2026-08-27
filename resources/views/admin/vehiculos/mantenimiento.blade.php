<x-app-layout>
    <x-slot:title>Mantenimiento {{ $vehicle->plate }} | AutoAlquiler</x-slot:title>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.vehiculos.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a vehículos
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">
            Registro de Mantenimiento: {{ $vehicle->plate }}
        </h1>

        <x-alert style="warning" class="mb-6">
            <strong>{{ $vehicle->brand }} {{ $vehicle->model }}</strong> pasará a estado
            <strong>Mantenimiento</strong> y no estará disponible para nuevas reservas hasta la fecha indicada.
        </x-alert>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6">
            <form action="{{ route('admin.vehiculos.mantenimiento.store', $vehicle) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="maintenance_notes" class="block text-sm font-semibold mb-1">
                        Motivo del mantenimiento <span class="text-[var(--danger)]">*</span>
                    </label>
                    <textarea id="maintenance_notes" name="maintenance_notes" rows="3"
                              placeholder="Ej: Cambio de aceite y revisión de frenos"
                              class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 py-2 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('maintenance_notes') border-[var(--danger)] @enderror">{{ old('maintenance_notes') }}</textarea>
                    @error('maintenance_notes')
                        <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="available_from" class="block text-sm font-semibold mb-1">
                        Fecha estimada de retorno <span class="text-[var(--danger)]">*</span>
                    </label>
                    <input type="date" id="available_from" name="available_from"
                           value="{{ old('available_from') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('available_from') border-[var(--danger)] @enderror">
                    @error('available_from')
                        <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <x-btn href="{{ route('admin.vehiculos.index') }}" style="outline" size="sm">Cancelar</x-btn>
                    <x-btn type="submit" size="sm">Poner en mantenimiento</x-btn>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
