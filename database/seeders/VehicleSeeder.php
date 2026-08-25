<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Category;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $economico  = Category::where('name', 'Económico')->first()->id;
        $intermedio = Category::where('name', 'Intermedio')->first()->id;
        $suv        = Category::where('name', 'SUV')->first()->id;
        $premium    = Category::where('name', 'Premium')->first()->id;
        $pickup     = Category::where('name', 'Pickup')->first()->id;
        $van        = Category::where('name', 'Van / Minivan')->first()->id;

        $vehicles = [
            [
                'category_id'        => $economico,
                'brand'              => 'Toyota',
                'model'              => 'Yaris',
                'plate'              => 'A123BC',
                'price_per_day'      => 35.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 2,
                'key_features'       => 'Aire acondicionado, Bluetooth, Cámara de reversa',
                'current_mileage'    => 12000,
                'current_fuel_level' => 100,
            ],
            [
                'category_id'        => $economico,
                'brand'              => 'Kia',
                'model'              => 'Rio',
                'plate'              => 'B456DE',
                'price_per_day'      => 32.00,
                'status'             => 'disponible',
                'transmission_type'  => 'manual',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 2,
                'key_features'       => 'Aire acondicionado, USB, Bluetooth',
                'current_mileage'    => 20500,
                'current_fuel_level' => 85,
            ],
            [
                'category_id'        => $intermedio,
                'brand'              => 'Toyota',
                'model'              => 'Corolla',
                'plate'              => 'C789FG',
                'price_per_day'      => 50.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 3,
                'key_features'       => 'Aire acondicionado, Apple CarPlay, Cámara de reversa, Control crucero',
                'current_mileage'    => 8000,
                'current_fuel_level' => 100,
            ],
            [
                'category_id'        => $intermedio,
                'brand'              => 'Honda',
                'model'              => 'Civic',
                'plate'              => 'D012HI',
                'price_per_day'      => 55.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 3,
                'key_features'       => 'Aire acondicionado, Android Auto, Sensores de estacionamiento',
                'current_mileage'    => 15300,
                'current_fuel_level' => 90,
            ],
            [
                'category_id'        => $suv,
                'brand'              => 'Toyota',
                'model'              => 'RAV4',
                'plate'              => 'E345JK',
                'price_per_day'      => 85.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'hibrido',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 4,
                'key_features'       => 'Aire acondicionado, 4x4, Apple CarPlay, Cámara 360°, Control crucero adaptativo',
                'current_mileage'    => 5000,
                'current_fuel_level' => 100,
            ],
            [
                'category_id'        => $suv,
                'brand'              => 'Hyundai',
                'model'              => 'Tucson',
                'plate'              => 'F678LM',
                'price_per_day'      => 75.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 4,
                'key_features'       => 'Aire acondicionado, Bluetooth, Cámara de reversa, Sensores de estacionamiento',
                'current_mileage'    => 18000,
                'current_fuel_level' => 75,
            ],
            [
                'category_id'        => $premium,
                'brand'              => 'BMW',
                'model'              => 'Serie 3',
                'plate'              => 'G901NO',
                'price_per_day'      => 150.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 3,
                'key_features'       => 'Cuero, Pantalla táctil, Apple CarPlay, Techo panorámico, Control crucero adaptativo',
                'current_mileage'    => 3000,
                'current_fuel_level' => 100,
            ],
            [
                'category_id'        => $pickup,
                'brand'              => 'Toyota',
                'model'              => 'Hilux',
                'plate'              => 'H234PQ',
                'price_per_day'      => 90.00,
                'status'             => 'disponible',
                'transmission_type'  => 'manual',
                'fuel_type'          => 'diesel',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 6,
                'key_features'       => 'Aire acondicionado, 4x4, Bluetooth, Doble cabina',
                'current_mileage'    => 30000,
                'current_fuel_level' => 100,
            ],
            [
                'category_id'        => $van,
                'brand'              => 'Kia',
                'model'              => 'Carnival',
                'plate'              => 'I567RS',
                'price_per_day'      => 110.00,
                'status'             => 'disponible',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'gasolina',
                'passenger_capacity' => 8,
                'luggage_capacity'   => 5,
                'key_features'       => 'Aire acondicionado, Apple CarPlay, Asientos reclinables, Cámara 360°',
                'current_mileage'    => 9500,
                'current_fuel_level' => 95,
            ],
            [
                'category_id'        => $premium,
                'brand'              => 'Tesla',
                'model'              => 'Model 3',
                'plate'              => 'J890TU',
                'price_per_day'      => 180.00,
                'status'             => 'en_mantenimiento',
                'transmission_type'  => 'automatica',
                'fuel_type'          => 'electrico',
                'passenger_capacity' => 5,
                'luggage_capacity'   => 3,
                'key_features'       => 'Pantalla táctil 15", Piloto automático, Carga rápida, Actualizaciones OTA',
                'current_mileage'    => 7200,
                'current_fuel_level' => 60,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
