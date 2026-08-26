<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'cliente')
            ->withCount('reservations')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.usuarios.index', compact('users'));
    }

    public function show(User $user)
    {
        $reservations = $user->reservations()
            ->with('vehicle')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.usuarios.show', compact('user', 'reservations'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,cliente'],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rol actualizado a «' . ucfirst($request->role) . '».');
    }
}
