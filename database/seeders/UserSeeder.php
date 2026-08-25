<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@alquiler.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'María López',
            'email'    => 'maria@example.com',
            'password' => Hash::make('password'),
            'role'     => 'cliente',
        ]);

        User::create([
            'name'     => 'Carlos Pérez',
            'email'    => 'carlos@example.com',
            'password' => Hash::make('password'),
            'role'     => 'cliente',
        ]);

        User::create([
            'name'     => 'Laura Ramírez',
            'email'    => 'laura@example.com',
            'password' => Hash::make('password'),
            'role'     => 'cliente',
        ]);
    }
}
