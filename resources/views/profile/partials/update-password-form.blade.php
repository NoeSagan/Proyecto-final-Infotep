<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">Cambiar contraseña</h2>
        <p class="mt-1 text-sm text-gray-500">Usa una contraseña segura y única para proteger tu cuenta.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-600 mb-1">Contraseña actual</label>
            <input id="update_password_current_password" type="password" name="current_password"
                   class="input @error('current_password', 'updatePassword') input-error @enderror"
                   required autocomplete="current-password"
                   placeholder="Tu contraseña actual">
            @error('current_password', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-600 mb-1">Nueva contraseña</label>
            <input id="update_password_password" type="password" name="password"
                   class="input @error('password', 'updatePassword') input-error @enderror"
                   required autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres">
            @error('password', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-600 mb-1">Confirmar nueva contraseña</label>
            <input id="update_password_password_confirmation" type="password" name="password_confirmation"
                   class="input @error('password_confirmation', 'updatePassword') input-error @enderror"
                   required autocomplete="new-password"
                   placeholder="Repite la nueva contraseña">
            @error('password_confirmation', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn btn-primary btn-sm">Guardar contraseña</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600">
                    Guardado correctamente.
                </p>
            @endif
        </div>
    </form>
</section>
