<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">Información del perfil</h2>
        <p class="mt-1 text-sm text-gray-500">Actualiza tu nombre y dirección de correo electrónico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-600 mb-1">Nombre completo</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="input @error('name') input-error @enderror"
                   required autofocus autocomplete="name"
                   placeholder="Tu nombre completo">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-600 mb-1">Correo electrónico</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="input @error('email') input-error @enderror"
                   required autocomplete="username"
                   placeholder="tu@correo.com">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        Tu correo no está verificado.
                        <button form="send-verification" class="text-blue-600 hover:underline text-sm font-medium">
                            Reenviar verificación
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-sm text-green-600 font-medium">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600">
                    Guardado correctamente.
                </p>
            @endif
        </div>
    </form>
</section>
