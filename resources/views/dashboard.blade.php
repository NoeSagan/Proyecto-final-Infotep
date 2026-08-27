<x-app-layout>
    <x-slot:title>Inicio | AutoAlquiler</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Accesos rápidos --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('vehiculos.index') }}"
               class="flex flex-col items-center text-center p-6 bg-gray-900 text-white border border-gray-900 rounded-[var(--radius)] shadow-sm hover:opacity-90 transition-opacity">
                <svg class="w-8 h-8 mb-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10l2-2zM13 6h3l3 5v5h-2"/>
                </svg>
                <p class="font-bold">Ver Catálogo</p>
                <p class="text-sm opacity-70 mt-0.5">Busca y reserva vehículos</p>
            </a>

            <a href="{{ route('mis-reservas.index') }}"
               class="flex flex-col items-center text-center p-6 bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] shadow-sm hover:border-[var(--secondary)] transition-colors">
                <svg class="w-8 h-8 mb-3 text-[var(--muted-foreground)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="font-bold text-[var(--foreground)]">Mis Reservas</p>
                <p class="text-sm text-[var(--muted-foreground)] mt-0.5">Gestiona tus reservas</p>
            </a>

            <a href="{{ route('favoritos.index') }}"
               class="flex flex-col items-center text-center p-6 bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] shadow-sm hover:border-[var(--secondary)] transition-colors">
                <svg class="w-8 h-8 mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <p class="font-bold text-[var(--foreground)]">Favoritos</p>
                <p class="text-sm text-[var(--muted-foreground)] mt-0.5">Tus vehículos guardados</p>
            </a>
        </div>

        {{-- Aviso de cancelación --}}
        <x-alert>
            Puedes cancelar reservas <strong>pendientes</strong> sin cargo. Reservas <strong>confirmadas</strong> canceladas con más de 48 h de anticipación también son gratuitas.
        </x-alert>

    </div>
</x-app-layout>
