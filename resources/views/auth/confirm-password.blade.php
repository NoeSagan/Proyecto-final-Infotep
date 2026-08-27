<x-guest-layout>
    <x-card>

        <x-card.header>
            <x-card.title>Área segura</x-card.title>
            <p>Por seguridad, confirma tu contraseña antes de continuar.</p>
        </x-card.header>

        <x-card.body>
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4" x-data="{ show: false }">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password"
                               class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('password') border-[var(--danger)] @enderror"
                               required autocomplete="current-password" placeholder="Tu contraseña actual">
                        <button type="button" @click="show = !show"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors"
                                :aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                            <svg x-show="!show" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <x-btn type="submit" class="w-full justify-center">Confirmar acceso</x-btn>
            </form>
        </x-card.body>

    </x-card>
</x-guest-layout>
