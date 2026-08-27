<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $eco  = Category::where('name', 'Económico')->value('id');
        $int  = Category::where('name', 'Intermedio')->value('id');
        $suv  = Category::where('name', 'SUV')->value('id');
        $prem = Category::where('name', 'Premium')->value('id');
        $pick = Category::where('name', 'Pickup')->value('id');
        $van  = Category::where('name', 'Van / Minivan')->value('id');

        $vehicles = [
            // ── Económico ──────────────────────────────────────────────────
            ['category_id' => $eco,  'brand' => 'Toyota',     'model' => 'Yaris',       'plate' => 'A001EC', 'price_per_day' => 35,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 2, 'current_mileage' => 12000, 'current_fuel_level' => 100, 'key_features' => 'Aire acondicionado, Bluetooth, Cámara de reversa'],
            ['category_id' => $eco,  'brand' => 'Kia',        'model' => 'Rio',         'plate' => 'A002EC', 'price_per_day' => 32,  'transmission_type' => 'manual',     'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 2, 'current_mileage' => 20500, 'current_fuel_level' => 85,  'key_features' => 'Aire acondicionado, USB, Bluetooth'],
            ['category_id' => $eco,  'brand' => 'Chevrolet',  'model' => 'Spark',       'plate' => 'A003EC', 'price_per_day' => 28,  'transmission_type' => 'manual',     'fuel_type' => 'gasolina',  'passenger_capacity' => 4, 'luggage_capacity' => 1, 'current_mileage' => 35000, 'current_fuel_level' => 70,  'key_features' => 'Aire acondicionado, Bluetooth'],
            ['category_id' => $eco,  'brand' => 'Nissan',     'model' => 'March',       'plate' => 'A004EC', 'price_per_day' => 30,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 2, 'current_mileage' => 18000, 'current_fuel_level' => 90,  'key_features' => 'Aire acondicionado, Bluetooth, USB'],
            ['category_id' => $eco,  'brand' => 'Renault',    'model' => 'Logan',       'plate' => 'A005EC', 'price_per_day' => 29,  'transmission_type' => 'manual',     'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 22000, 'current_fuel_level' => 80,  'key_features' => 'Aire acondicionado, Bluetooth'],
            ['category_id' => $eco,  'brand' => 'Suzuki',     'model' => 'Swift',       'plate' => 'A006EC', 'price_per_day' => 31,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 2, 'current_mileage' => 9000,  'current_fuel_level' => 100, 'key_features' => 'Aire acondicionado, Apple CarPlay'],

            // ── Intermedio ─────────────────────────────────────────────────
            ['category_id' => $int,  'brand' => 'Toyota',     'model' => 'Corolla',     'plate' => 'B001IN', 'price_per_day' => 50,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 8000,  'current_fuel_level' => 100, 'key_features' => 'Aire acondicionado, Apple CarPlay, Cámara de reversa, Control crucero'],
            ['category_id' => $int,  'brand' => 'Honda',      'model' => 'Civic',       'plate' => 'B002IN', 'price_per_day' => 55,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 15300, 'current_fuel_level' => 90,  'key_features' => 'Aire acondicionado, Android Auto, Sensores de estacionamiento'],
            ['category_id' => $int,  'brand' => 'Mazda',      'model' => '3',           'plate' => 'B003IN', 'price_per_day' => 52,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 11000, 'current_fuel_level' => 95,  'key_features' => 'Aire acondicionado, Apple CarPlay, Head-up Display'],
            ['category_id' => $int,  'brand' => 'Volkswagen', 'model' => 'Jetta',       'plate' => 'B004IN', 'price_per_day' => 48,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 14000, 'current_fuel_level' => 85,  'key_features' => 'Aire acondicionado, Bluetooth, Pantalla táctil'],
            ['category_id' => $int,  'brand' => 'Nissan',     'model' => 'Sentra',      'plate' => 'B005IN', 'price_per_day' => 45,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 19000, 'current_fuel_level' => 75,  'key_features' => 'Aire acondicionado, Android Auto'],
            ['category_id' => $int,  'brand' => 'Hyundai',    'model' => 'Elantra',     'plate' => 'B006IN', 'price_per_day' => 47,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 13000, 'current_fuel_level' => 88,  'key_features' => 'Aire acondicionado, Apple CarPlay, Cámara de reversa'],

            // ── SUV ────────────────────────────────────────────────────────
            ['category_id' => $suv,  'brand' => 'Toyota',     'model' => 'RAV4',        'plate' => 'C001SV', 'price_per_day' => 85,  'transmission_type' => 'automatica', 'fuel_type' => 'hibrido',   'passenger_capacity' => 5, 'luggage_capacity' => 4, 'current_mileage' => 5000,  'current_fuel_level' => 100, 'key_features' => 'Aire acondicionado, 4x4, Apple CarPlay, Cámara 360°, Control crucero adaptativo'],
            ['category_id' => $suv,  'brand' => 'Hyundai',    'model' => 'Tucson',      'plate' => 'C002SV', 'price_per_day' => 75,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 4, 'current_mileage' => 18000, 'current_fuel_level' => 75,  'key_features' => 'Aire acondicionado, Bluetooth, Cámara de reversa, Sensores de estacionamiento'],
            ['category_id' => $suv,  'brand' => 'Jeep',       'model' => 'Wrangler',    'plate' => 'C003SV', 'price_per_day' => 120, 'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 7500,  'current_fuel_level' => 100, 'key_features' => '4x4, Techo removible, Apple CarPlay, Control tracción'],
            ['category_id' => $suv,  'brand' => 'Ford',       'model' => 'Escape',      'plate' => 'C004SV', 'price_per_day' => 80,  'transmission_type' => 'automatica', 'fuel_type' => 'hibrido',   'passenger_capacity' => 5, 'luggage_capacity' => 4, 'current_mileage' => 10000, 'current_fuel_level' => 95,  'key_features' => 'Aire acondicionado, SYNC 4, Cámara 360°'],
            ['category_id' => $suv,  'brand' => 'Nissan',     'model' => 'X-Trail',     'plate' => 'C005SV', 'price_per_day' => 78,  'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 7, 'luggage_capacity' => 4, 'current_mileage' => 16000, 'current_fuel_level' => 80,  'key_features' => 'Aire acondicionado, Android Auto, 7 pasajeros, Cámara de reversa'],
            ['category_id' => $suv,  'brand' => 'Mitsubishi', 'model' => 'Outlander',   'plate' => 'C006SV', 'price_per_day' => 82,  'transmission_type' => 'automatica', 'fuel_type' => 'hibrido',   'passenger_capacity' => 7, 'luggage_capacity' => 4, 'current_mileage' => 6000,  'current_fuel_level' => 100, 'key_features' => 'Plug-in Hybrid, 7 pasajeros, Apple CarPlay, AWD'],

            // ── Premium ────────────────────────────────────────────────────
            ['category_id' => $prem, 'brand' => 'BMW',        'model' => 'Serie 3',     'plate' => 'D001PM', 'price_per_day' => 150, 'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 3000,  'current_fuel_level' => 100, 'key_features' => 'Cuero, Pantalla táctil, Apple CarPlay, Techo panorámico, Control crucero adaptativo'],
            ['category_id' => $prem, 'brand' => 'Tesla',      'model' => 'Model 3',     'plate' => 'D002PM', 'price_per_day' => 180, 'transmission_type' => 'automatica', 'fuel_type' => 'electrico', 'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 7200,  'current_fuel_level' => 60,  'key_features' => 'Pantalla táctil 15", Piloto automático, Carga rápida, Actualizaciones OTA', 'status' => 'en_mantenimiento'],
            ['category_id' => $prem, 'brand' => 'Mercedes-Benz', 'model' => 'Clase C',  'plate' => 'D003PM', 'price_per_day' => 200, 'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 2000,  'current_fuel_level' => 100, 'key_features' => 'MBUX, Cuero Nappa, Techo panorámico, Asistente de conducción'],
            ['category_id' => $prem, 'brand' => 'Audi',       'model' => 'A4',          'plate' => 'D004PM', 'price_per_day' => 170, 'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 4000,  'current_fuel_level' => 100, 'key_features' => 'MMI Touch, Cuero, Virtual Cockpit, Quattro AWD'],
            ['category_id' => $prem, 'brand' => 'Lexus',      'model' => 'IS',          'plate' => 'D005PM', 'price_per_day' => 160, 'transmission_type' => 'automatica', 'fuel_type' => 'hibrido',   'passenger_capacity' => 5, 'luggage_capacity' => 3, 'current_mileage' => 5500,  'current_fuel_level' => 100, 'key_features' => 'Cuero, Mark Levinson Audio, Híbrido, Control crucero radar'],

            // ── Pickup ─────────────────────────────────────────────────────
            ['category_id' => $pick, 'brand' => 'Toyota',     'model' => 'Hilux',       'plate' => 'E001PK', 'price_per_day' => 90,  'transmission_type' => 'manual',     'fuel_type' => 'diesel',    'passenger_capacity' => 5, 'luggage_capacity' => 6, 'current_mileage' => 30000, 'current_fuel_level' => 100, 'key_features' => 'Aire acondicionado, 4x4, Bluetooth, Doble cabina'],
            ['category_id' => $pick, 'brand' => 'Ford',       'model' => 'Ranger',      'plate' => 'E002PK', 'price_per_day' => 95,  'transmission_type' => 'automatica', 'fuel_type' => 'diesel',    'passenger_capacity' => 5, 'luggage_capacity' => 6, 'current_mileage' => 22000, 'current_fuel_level' => 95,  'key_features' => 'Aire acondicionado, SYNC 3, 4x4, Control de descenso'],
            ['category_id' => $pick, 'brand' => 'Mitsubishi', 'model' => 'L200',        'plate' => 'E003PK', 'price_per_day' => 85,  'transmission_type' => 'manual',     'fuel_type' => 'diesel',    'passenger_capacity' => 5, 'luggage_capacity' => 6, 'current_mileage' => 28000, 'current_fuel_level' => 90,  'key_features' => 'Aire acondicionado, 4x4, Bluetooth, Super Select 4WD'],

            // ── Van / Minivan ──────────────────────────────────────────────
            ['category_id' => $van,  'brand' => 'Kia',        'model' => 'Carnival',    'plate' => 'F001VN', 'price_per_day' => 110, 'transmission_type' => 'automatica', 'fuel_type' => 'gasolina',  'passenger_capacity' => 8, 'luggage_capacity' => 5, 'current_mileage' => 9500,  'current_fuel_level' => 95,  'key_features' => 'Aire acondicionado, Apple CarPlay, Asientos reclinables, Cámara 360°'],
            ['category_id' => $van,  'brand' => 'Toyota',     'model' => 'Sienna',      'plate' => 'F002VN', 'price_per_day' => 120, 'transmission_type' => 'automatica', 'fuel_type' => 'hibrido',   'passenger_capacity' => 8, 'luggage_capacity' => 5, 'current_mileage' => 6000,  'current_fuel_level' => 100, 'key_features' => 'Híbrido AWD, 8 pasajeros, Pantalla trasera, Puertas correderas eléctricas'],
            ['category_id' => $van,  'brand' => 'Volkswagen', 'model' => 'Caravelle',   'plate' => 'F003VN', 'price_per_day' => 130, 'transmission_type' => 'automatica', 'fuel_type' => 'diesel',    'passenger_capacity' => 9, 'luggage_capacity' => 6, 'current_mileage' => 14000, 'current_fuel_level' => 85,  'key_features' => '9 pasajeros, Bluetooth, Climatizador doble, Cámara de reversa'],
        ];

        foreach ($vehicles as $data) {
            Vehicle::create(array_merge(['status' => 'disponible'], $data));
        }
    }
}
