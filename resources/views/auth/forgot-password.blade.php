<x-guest-layout>
    <x-card>

        <x-card.header>
            <x-card.title>Recuperar contraseña</x-card.title>
            <p>Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>
        </x-card.header>

        <x-card.body>
            @if (session('status'))
                <x-alert style="success" class="mb-4">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('email') border-[var(--danger)] @enderror"
                           required autofocus autocomplete="username" placeholder="tu@correo.com">
                    @error('email') <p class="text-[var(--danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <x-btn type="submit" class="w-full justify-center">Enviar enlace de recuperación</x-btn>

                <x-btn href="{{ route('login') }}" style="ghost" class="w-full justify-center">
                    Volver al inicio de sesión
                </x-btn>
            </form>
        </x-card.body>

    </x-card>
</x-guest-layout>
