<x-app-layout>
    <x-slot:title>Nueva Categoría | AutoAlquiler</x-slot:title>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.categorias.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a categorías
        </a>

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-6">Nueva Categoría</h1>

        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6">
            <form action="{{ route('admin.categorias.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold mb-1">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('name') border-[var(--danger)] @enderror"
                           required placeholder="Ej: SUV, Sedán, Compacto">
                    @error('name')
                        <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold mb-1">
                        Descripción <span class="font-normal text-[var(--muted-foreground)]">(opcional)</span>
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 py-2 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('description') border-[var(--danger)] @enderror"
                              placeholder="Describe brevemente esta categoría de vehículos...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <x-btn href="{{ route('admin.categorias.index') }}" style="outline" size="sm">Cancelar</x-btn>
                    <x-btn type="submit" size="sm">Guardar categoría</x-btn>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
