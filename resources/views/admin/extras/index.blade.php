<x-app-layout>
    <x-slot:title>Extras | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="flex items-center justify-between mb-5">
            <h1 class="text-xl font-semibold text-[var(--foreground)]">Gestión de Extras</h1>
            <x-btn href="{{ route('admin.extras.create') }}" size="sm">+ Nuevo Extra</x-btn>
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-4">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert style="danger" class="mb-4">{{ session('error') }}</x-alert>
        @endif

        <x-table>
            <x-table-header>
                <x-table-row>
                    <x-table-head>#</x-table-head>
                    <x-table-head>Nombre</x-table-head>
                    <x-table-head>Tipo de selección</x-table-head>
                    <x-table-head align="end">Precio / día</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($extras as $i => $extra)
                    <x-table-row>
                        <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                            {{ str_pad($extras->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}
                        </x-table-head>
                        <x-table-cell class="font-medium">{{ $extra->name }}</x-table-cell>
                        <x-table-cell>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[var(--muted)] text-[var(--muted-foreground)]">
                                {{ $extra->selection_type === 'single' ? 'Checkbox' : 'Cantidad' }}
                            </span>
                        </x-table-cell>
                        <x-table-cell align="end" class="font-semibold">
                            $ {{ number_format($extra->price, 2) }}
                        </x-table-cell>
                        <x-table-cell align="end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('admin.extras.edit', $extra) }}" style="outline" size="sm">Editar</x-btn>
                                <form action="{{ route('admin.extras.destroy', $extra) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar el extra «{{ $extra->name }}»?')">
                                    @csrf @method('DELETE')
                                    <x-btn type="submit" style="danger" size="sm">Eliminar</x-btn>
                                </form>
                            </div>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="5" align="center" class="py-10 text-[var(--muted-foreground)]">
                            No hay extras registrados.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>

        <div class="mt-4">
            {{ $extras->links() }}
        </div>

    </div>
</x-app-layout>
