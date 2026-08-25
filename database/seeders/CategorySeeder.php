<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Económico',    'description' => 'Vehículos compactos, ideales para ciudad y bajo consumo.'],
            ['name' => 'Intermedio',   'description' => 'Sedanes medianos con mayor comodidad y espacio.'],
            ['name' => 'SUV',          'description' => 'Vehículos todo terreno con amplia capacidad de pasajeros y equipaje.'],
            ['name' => 'Premium',      'description' => 'Vehículos de lujo con equipamiento de alta gama.'],
            ['name' => 'Pickup',       'description' => 'Camionetas para carga y terrenos difíciles.'],
            ['name' => 'Van / Minivan','description' => 'Ideales para grupos familiares o viajes largos.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
