<x-app-layout>
    <x-slot:title>{{ $user->name }} | AutoAlquiler</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('admin.usuarios.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a usuarios
        </a>

        @if (session('success'))
            <x-alert style="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Datos del usuario --}}
            <div class="lg:col-span-1">
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] p-6 space-y-4">

                    <div>
                        <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Datos del cliente</p>
                        <p class="font-bold text-[var(--foreground)] text-lg">{{ $user->name }}</p>
                        <p class="text-sm text-[var(--muted-foreground)] mt-0.5">{{ $user->email }}</p>
                        <p class="text-xs text-[var(--muted-foreground)] mt-1">Registrado: {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>

                    <div class="border-t border-[var(--border)] pt-4">
                        <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-3">Cambiar rol</p>
                        <form action="{{ route('admin.usuarios.rol', $user) }}" method="POST" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role"
                                    class="flex-1 border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--ring)]">
                                <option value="cliente" {{ $user->role === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-btn type="submit" size="sm">Guardar</x-btn>
                        </form>
                    </div>

                </div>
            </div>

            {{-- Historial de reservas --}}
            <div class="lg:col-span-2">
                <div class="bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-[var(--border)]">
                        <h3 class="font-semibold text-[var(--foreground)]">
                            Historial de reservas ({{ count($reservations) }})
                        </h3>
                    </div>

                    @forelse ($reservations as $r)
                        <div class="px-5 py-4 border-b border-[var(--border)] last:border-0 flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-[var(--foreground)] text-sm">
                                    {{ $r->vehicle->brand }} {{ $r->vehicle->model }}
                                </p>
                                <p class="text-xs text-[var(--muted-foreground)] mt-0.5">
                                    {{ $r->start_date->format('d/m/Y') }} al {{ $r->end_date->format('d/m/Y') }}
                                    · $ {{ number_format($r->total_cost, 2) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                    {{ ucfirst($r->status) }}
                                </span>
                                <x-btn href="{{ route('admin.reservas.show', $r) }}" size="sm">Ver</x-btn>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-[var(--muted-foreground)] text-sm">
                            Sin reservas registradas.
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
