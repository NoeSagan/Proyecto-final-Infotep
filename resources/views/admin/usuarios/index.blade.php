<x-app-layout>
    <x-slot:title>Usuarios | AutoAlquiler</x-slot:title>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="flex items-center justify-between mb-5">
            <h1 class="text-xl font-semibold text-[var(--foreground)]">Gestión de Usuarios</h1>
        </div>

        {{-- Buscador --}}
        <form method="GET" action="{{ route('admin.usuarios.index') }}"
              class="flex flex-wrap gap-2 mb-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nombre o correo..."
                   class="flex-1 min-w-0 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-8 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
            <x-btn type="submit" size="sm">Buscar</x-btn>
            @if (request('search'))
                <x-btn href="{{ route('admin.usuarios.index') }}" style="outline" size="sm">Limpiar</x-btn>
            @endif
        </form>

        <div class="overflow-x-auto">
        <x-table>
            <x-table-header>
                <x-table-row>
                    <x-table-head>#</x-table-head>
                    <x-table-head>Nombre</x-table-head>
                    <x-table-head>Correo</x-table-head>
                    <x-table-head>Reservas</x-table-head>
                    <x-table-head>Registro</x-table-head>
                    <x-table-head align="end">Acciones</x-table-head>
                </x-table-row>
            </x-table-header>
            <x-table-body>
                @forelse ($users as $i => $user)
                    <x-table-row>
                        <x-table-head class="text-[var(--muted-foreground)] font-mono text-xs">
                            {{ str_pad($users->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}
                        </x-table-head>
                        <x-table-cell class="font-medium">{{ $user->name }}</x-table-cell>
                        <x-table-cell class="text-[var(--muted-foreground)]">{{ $user->email }}</x-table-cell>
                        <x-table-cell class="text-[var(--muted-foreground)]">{{ $user->reservations_count }}</x-table-cell>
                        <x-table-cell class="text-[var(--muted-foreground)]">{{ $user->created_at->format('d/m/Y') }}</x-table-cell>
                        <x-table-cell align="end">
                            <x-btn href="{{ route('admin.usuarios.show', $user) }}" size="sm">Ver</x-btn>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="6" align="center" class="py-10 text-[var(--muted-foreground)]">
                            {{ request('search') ? 'No se encontraron usuarios con esa búsqueda.' : 'No hay clientes registrados.' }}
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>

    </div>
</x-app-layout>
