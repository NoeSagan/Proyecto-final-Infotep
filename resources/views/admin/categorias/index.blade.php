<x-app-layout>
    <x-slot:title>Categorías | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="flex items-center justify-between flex-wrap gap-3 mb-5">
            <h1 class="text-xl font-semibold text-[var(--foreground)]">Gestión de Categorías</h1>
            <x-btn href="{{ route('admin.categorias.create') }}" size="sm">+ Nueva Categoría</x-btn>
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-4">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert style="danger" class="mb-4">{{ session('error') }}</x-alert>
        @endif

        <div class="overflow-x-auto">
        <x-table>
            <x-table-header>
                <x-table-row>
                    <x-table-head>#</x-table-head>
                    <x-table-head>Nombre</x-table-head>
                    <x-table-head>Descripción</x-table-head>
                    <x-table-head>Vehículos</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($categories as $i => $category)
                    <x-table-row>
                        <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                            {{ str_pad($categories->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}
                        </x-table-head>
                        <x-table-cell class="font-medium">{{ $category->name }}</x-table-cell>
                        <x-table-cell class="max-w-xs truncate text-[var(--muted-foreground)]">{{ $category->description ?? '—' }}</x-table-cell>
                        <x-table-cell>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[var(--muted)] text-[var(--muted-foreground)]">
                                {{ $category->vehicles_count }}
                            </span>
                        </x-table-cell>
                        <x-table-cell align="end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('admin.categorias.edit', $category) }}" style="outline" size="sm">Editar</x-btn>
                                <form action="{{ route('admin.categorias.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar la categoría «{{ $category->name }}»?')">
                                    @csrf @method('DELETE')
                                    <x-btn type="submit" style="danger" size="sm">Eliminar</x-btn>
                                </form>
                            </div>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="5" align="center" class="py-10 text-[var(--muted-foreground)]">
                            No hay categorías registradas.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>

    </div>
</x-app-layout>
