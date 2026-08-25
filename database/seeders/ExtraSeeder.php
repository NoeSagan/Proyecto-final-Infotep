<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    public function run(): void
    {
        $extras = [
            ['name' => 'GPS',                          'price' => 5.00],
            ['name' => 'Asiento para bebés',           'price' => 8.00],
            ['name' => 'Asistencia en carretera',      'price' => 12.00],
            ['name' => 'Seguro de ocupantes',          'price' => 15.00],
            ['name' => 'Portaequipajes',               'price' => 10.00],
            ['name' => 'Portabicicletas',              'price' => 10.00],
            ['name' => 'Conductor adicional',          'price' => 7.00],
            ['name' => 'WiFi portátil',                'price' => 6.00],
        ];

        foreach ($extras as $extra) {
            Extra::create($extra);
        }
    }
}
