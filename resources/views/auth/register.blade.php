<x-guest-layout>
    <x-card>

        <x-card.header>
            <x-card.title>Crear cuenta</x-card.title>
            <p>Completa los datos para registrarte.</p>
        </x-card.header>

        <x-card.body>
            <form method="POST" action="{{ route('register') }}" class="space-y-4"
                  x-data="{
                      show: false,
                      password: '',
                      confirm: '',
                      get hasLength()  { return this.password.length >= 8 },
                      get hasUpper()   { return /[A-Z]/.test(this.password) },
                      get hasDigit()   { return /[0-9]/.test(this.password) },
                      get hasSpecial() { return /[-_@&#]/.test(this.password) },
                      get allValid()   { return this.hasLength && this.hasUpper && this.hasDigit && this.hasSpecial },
                      get matches()    { return this.confirm !== '' && this.password === this.confirm },
                      get mismatch()   { return this.confirm !== '' && this.password !== this.confirm }
                  }">
                @csrf

                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Nombre completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('name') border-[var(--danger)] @enderror"
                           required autofocus autocomplete="name" placeholder="Tu nombre completo">
                    @error('name') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('email') border-[var(--danger)] @enderror"
                           required autocomplete="username" placeholder="tu@correo.com">
                    @error('email') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="password" name="password"
                               x-model="password"
                               :type="show ? 'text' : 'password'"
                               class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 transition-colors"
                               :class="password.length === 0
                                   ? 'border-[var(--border)] focus:ring-[var(--ring)]'
                                   : allValid
                                       ? 'border-green-500 focus:ring-green-500/30'
                                       : 'border-red-500 focus:ring-red-500/30'"
                               required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
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

                    {{-- Condiciones de contraseña --}}
                    <ul class="mt-2 space-y-1" x-show="password.length > 0" x-cloak>
                        {{-- Longitud --}}
                        <li class="flex items-center gap-1.5 text-xs transition-colors"
                            :class="hasLength ? 'text-green-600' : 'text-red-500'">
                            <svg x-show="hasLength" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg x-show="!hasLength" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Mínimo 8 caracteres
                        </li>
                        {{-- Mayúscula --}}
                        <li class="flex items-center gap-1.5 text-xs transition-colors"
                            :class="hasUpper ? 'text-green-600' : 'text-red-500'">
                            <svg x-show="hasUpper" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg x-show="!hasUpper" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Al menos una mayúscula (A-Z)
                        </li>
                        {{-- Dígito --}}
                        <li class="flex items-center gap-1.5 text-xs transition-colors"
                            :class="hasDigit ? 'text-green-600' : 'text-red-500'">
                            <svg x-show="hasDigit" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg x-show="!hasDigit" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Al menos un número (0-9)
                        </li>
                        {{-- Carácter especial --}}
                        <li class="flex items-center gap-1.5 text-xs transition-colors"
                            :class="hasSpecial ? 'text-green-600' : 'text-red-500'">
                            <svg x-show="hasSpecial" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg x-show="!hasSpecial" class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Al menos un carácter especial (-_@&#)
                        </li>
                    </ul>

                    @error('password') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation"
                               x-model="confirm"
                               :type="show ? 'text' : 'password'"
                               class="w-full border rounded-[var(--radius)] bg-[var(--input)] px-3 pr-10 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 transition-colors"
                               :class="matches
                                   ? 'border-green-500 focus:ring-green-500/30'
                                   : mismatch
                                       ? 'border-red-500 focus:ring-red-500/30'
                                       : 'border-[var(--border)] focus:ring-[var(--ring)]'"
                               required autocomplete="new-password" placeholder="Repite tu contraseña">
                        <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
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

                    <p x-show="matches" x-cloak
                       class="flex items-center gap-1 text-green-600 text-xs mt-1">
                        <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Las contraseñas coinciden
                    </p>
                    <p x-show="mismatch" x-cloak
                       class="flex items-center gap-1 text-red-500 text-xs mt-1">
                        <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Las contraseñas no coinciden
                    </p>
                    @error('password_confirmation') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <x-btn type="submit" class="w-full justify-center">Crear cuenta</x-btn>
            </form>
        </x-card.body>

        <x-card.footer class="flex-col sm:flex-col items-stretch">
            <div class="relative py-2">
                <hr class="border-[var(--border)]">
                <span class="absolute inset-x-0 top-1/2 -translate-y-1/2 text-center">
                    <span class="bg-[var(--card)] px-3 text-xs opacity-50">¿Ya tienes cuenta?</span>
                </span>
            </div>
            <x-btn href="{{ route('login') }}" style="outline" class="w-full justify-center">
                Iniciar sesión
            </x-btn>
        </x-card.footer>

    </x-card>
</x-guest-layout>
