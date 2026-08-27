<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-gray-500">
            Una vez que elimines tu cuenta, todos los datos se borrarán de forma permanente. Esta acción no se puede deshacer.
        </p>
    </header>

    <button type="button" class="btn btn-danger btn-sm"
            onclick="document.getElementById('modal-delete-account').showModal()">
        Eliminar cuenta
    </button>

    <dialog id="modal-delete-account" class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full">
        <h3 class="text-lg font-bold text-gray-900">¿Eliminar cuenta permanentemente?</h3>
        <p class="py-3 text-sm text-gray-600">
            Esta acción es irreversible. Ingresa tu contraseña para confirmar.
        </p>

        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('delete')

            <div>
                <label for="delete_password" class="block text-sm font-semibold text-gray-600 mb-1">Contraseña actual</label>
                <input id="delete_password" type="password" name="password"
                       class="input @error('password', 'userDeletion') input-error @enderror"
                       required
                       placeholder="Tu contraseña actual">
                @error('password', 'userDeletion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('modal-delete-account').close()">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
            </div>
        </form>
    </dialog>

    @if ($errors->userDeletion->isNotEmpty())
        <script>document.getElementById('modal-delete-account').showModal();</script>
    @endif
</section>
