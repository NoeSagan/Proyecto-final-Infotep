<x-app-layout>
    <x-slot:title>Editar Extra | AutoAlquiler</x-slot:title>

    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.extras.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a extras
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">Editar Extra</h1>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6">
            <form action="{{ route('admin.extras.update', $extra) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold mb-1">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $extra->name) }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('name') border-[var(--danger)] @enderror"
                           required placeholder="Ej: GPS, Silla para bebé, WiFi">
                    @error('name')
                        <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-semibold mb-1">Precio por día (USD)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $extra->price) }}"
                           min="0" max="999" step="0.01"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('price') border-[var(--danger)] @enderror"
                           required placeholder="0.00">
                    @error('price')
                        <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="selection_type" class="block text-sm font-semibold mb-1">Tipo de selección</label>
                    <select id="selection_type" name="selection_type"
                            class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('selection_type') border-[var(--danger)] @enderror">
                        <option value="single"   {{ old('selection_type', $extra->selection_type) === 'single'   ? 'selected' : '' }}>Selección única (checkbox)</option>
                        <option value="multiple" {{ old('selection_type', $extra->selection_type) === 'multiple' ? 'selected' : '' }}>Cantidad (número de unidades)</option>
                    </select>
                    @error('selection_type')
                        <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <x-btn href="{{ route('admin.extras.index') }}" style="outline" size="sm">Cancelar</x-btn>
                    <x-btn type="submit" size="sm">Actualizar extra</x-btn>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
