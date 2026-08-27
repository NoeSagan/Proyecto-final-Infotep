<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    public function run(): void
    {
        $extras = [
            ['name' => 'GPS',                     'price' => 5.00,  'selection_type' => 'single'],
            ['name' => 'Asiento para bebés',      'price' => 8.00,  'selection_type' => 'multiple'],
            ['name' => 'Asistencia en carretera', 'price' => 12.00, 'selection_type' => 'single'],
            ['name' => 'Seguro de ocupantes',     'price' => 15.00, 'selection_type' => 'single'],
            ['name' => 'Portaequipajes',          'price' => 10.00, 'selection_type' => 'single'],
            ['name' => 'Portabicicletas',         'price' => 10.00, 'selection_type' => 'multiple'],
            ['name' => 'WiFi portátil',           'price' => 6.00,  'selection_type' => 'single'],
        ];

        foreach ($extras as $extra) {
            Extra::create($extra);
        }
    }
}
