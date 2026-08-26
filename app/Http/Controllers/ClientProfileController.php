<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClientProfileController extends Controller
{
    public function edit()
    {
        return view('perfil.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password'      => ['nullable', 'string'],
            'password'              => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'name.required'            => 'El nombre es obligatorio.',
            'email.required'           => 'El correo es obligatorio.',
            'email.unique'             => 'Ese correo ya está en uso.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        // Cambio de contraseña solo si la completó
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
