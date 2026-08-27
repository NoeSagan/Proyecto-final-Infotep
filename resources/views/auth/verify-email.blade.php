<x-guest-layout>
    <x-card>

        <x-card.header>
            <x-card.title>Verifica tu correo</x-card.title>
            <p>Antes de continuar, haz clic en el enlace de verificación que enviamos a tu correo. Si no lo recibiste, podemos enviarte uno nuevo.</p>
        </x-card.header>

        <x-card.body>
            @if (session('status') == 'verification-link-sent')
                <x-alert style="success" class="mb-4">
                    Se ha enviado un nuevo enlace de verificación a tu correo.
                </x-alert>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
                @csrf
                <x-btn type="submit" class="w-full justify-center">Reenviar correo de verificación</x-btn>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <x-btn type="submit" style="ghost" class="w-full justify-center">Cerrar sesión</x-btn>
            </form>
        </x-card.body>

    </x-card>
</x-guest-layout>
