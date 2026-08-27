<x-app-layout>
    <x-slot:title>Mi Perfil | AutoAlquiler</x-slot:title>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-5">Mi Perfil</h1>

        @if (session('success'))
            <x-alert style="success" class="mb-4">{{ session('success') }}</x-alert>
        @endif

        <x-card>
            <x-card.body>
                <form action="{{ route('perfil.update') }}" method="POST" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf
                    @method('PUT')

                    {{-- Datos personales --}}
                    <div class="border-t border-[var(--border)] pt-4">
                        <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-4">Datos personales</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Nombre completo</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('name') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                               required placeholder="Tu nombre completo">
                        @error('name') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('email') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                               required placeholder="tu@correo.com">
                        @error('email') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Cambiar contraseña --}}
                    <div class="border-t border-[var(--border)] pt-4">
                        <p class="text-xs font-semibold text-[var(--muted-foreground)] uppercase tracking-wider mb-1">Cambiar contraseña</p>
                        <p class="text-xs text-[var(--muted-foreground)] mb-4">Déjalo en blanco si no deseas cambiarla.</p>
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-medium mb-1">Contraseña actual</label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'"
                                   id="current_password" name="current_password"
                                   class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('current_password') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                                   autocomplete="current-password" placeholder="Tu contraseña actual">
                            <button type="button" @click="showCurrent = !showCurrent"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                                <svg x-show="!showCurrent" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showCurrent" x-cloak class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Nueva contraseña</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'"
                                   id="password" name="password"
                                   class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] {{ $errors->has('password') ? 'border-[var(--danger)]' : 'border-[var(--border)]' }}"
                                   autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                                <svg x-show="!showNew" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showNew" x-cloak class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar nueva contraseña</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'"
                                   id="password_confirmation" name="password_confirmation"
                                   class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                                   autocomplete="new-password" placeholder="Repite la nueva contraseña">
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
                                <svg x-show="!showConfirm" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showConfirm" x-cloak class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <x-btn type="submit">Guardar cambios</x-btn>
                    </div>
                </form>
            </x-card.body>
        </x-card>

    </div>
</x-app-layout>
