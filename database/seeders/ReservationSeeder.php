<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Extra;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $maria   = User::where('email', 'maria@example.com')->first();
        $carlos  = User::where('email', 'carlos@example.com')->first();
        $laura   = User::where('email', 'laura@example.com')->first();

        $corolla = Vehicle::where('model', 'Corolla')->first();
        $rav4    = Vehicle::where('model', 'RAV4')->first();
        $hilux   = Vehicle::where('model', 'Hilux')->first();
        $civic   = Vehicle::where('model', 'Civic')->first();
        $bmw     = Vehicle::where('model', 'Serie 3')->first();
        $yaris   = Vehicle::where('model', 'Yaris')->first();

        $gps      = Extra::where('name', 'GPS')->first();
        $seguro   = Extra::where('name', 'Seguro de ocupantes')->first();
        $asiento  = Extra::where('name', 'Asiento para bebés')->first();
        $asist    = Extra::where('name', 'Asistencia en carretera')->first();

        // Confirmada (activa, aporta ganancias)
        $r1 = Reservation::create([
            'user_id'             => $maria->id,
            'vehicle_id'          => $corolla->id,
            'start_date'          => now()->subDays(3),
            'end_date'            => now()->addDays(2),
            'passenger_count'     => 3,
            'total_cost'          => 280.00,
            'status'              => 'confirmada',
            'delivery_plate'      => $corolla->plate,
            'delivery_mileage'    => $corolla->current_mileage,
            'delivery_fuel_level' => $corolla->current_fuel_level,
        ]);
        $r1->extras()->attach($gps->id, ['quantity' => 1]);
        $r1->extras()->attach($seguro->id, ['quantity' => 1]);

        // Confirmada (activa)
        $r2 = Reservation::create([
            'user_id'             => $carlos->id,
            'vehicle_id'          => $rav4->id,
            'start_date'          => now()->subDay(),
            'end_date'            => now()->addDays(4),
            'passenger_count'     => 4,
            'total_cost'          => 450.00,
            'status'              => 'confirmada',
            'delivery_plate'      => $rav4->plate,
            'delivery_mileage'    => $rav4->current_mileage,
            'delivery_fuel_level' => $rav4->current_fuel_level,
        ]);
        $r2->extras()->attach($gps->id, ['quantity' => 1]);
        $r2->extras()->attach($asiento->id, ['quantity' => 1]);
        $r2->extras()->attach($asist->id, ['quantity' => 1]);

        // Pendiente (en espera de pago)
        $r3 = Reservation::create([
            'user_id'         => $laura->id,
            'vehicle_id'      => $civic->id,
            'start_date'      => now()->addDays(2),
            'end_date'        => now()->addDays(5),
            'passenger_count' => 2,
            'total_cost'      => 180.00,
            'status'          => 'pendiente',
        ]);

        // Completada (historial de ganancias)
        $r4 = Reservation::create([
            'user_id'             => $maria->id,
            'vehicle_id'          => $hilux->id,
            'start_date'          => now()->subDays(15),
            'end_date'            => now()->subDays(10),
            'passenger_count'     => 2,
            'total_cost'          => 462.00,
            'status'              => 'completada',
            'delivery_plate'      => $hilux->plate,
            'delivery_mileage'    => $hilux->current_mileage,
            'delivery_fuel_level' => $hilux->current_fuel_level,
        ]);
        $r4->extras()->attach($asist->id, ['quantity' => 1]);

        // Completada
        $r5 = Reservation::create([
            'user_id'             => $carlos->id,
            'vehicle_id'          => $bmw->id,
            'start_date'          => now()->subDays(20),
            'end_date'            => now()->subDays(17),
            'passenger_count'     => 1,
            'total_cost'          => 465.00,
            'status'              => 'completada',
            'delivery_plate'      => $bmw->plate,
            'delivery_mileage'    => $bmw->current_mileage,
            'delivery_fuel_level' => $bmw->current_fuel_level,
        ]);

        // Cancelada
        Reservation::create([
            'user_id'         => $laura->id,
            'vehicle_id'      => $yaris->id,
            'start_date'      => now()->subDays(5),
            'end_date'        => now()->subDays(3),
            'passenger_count' => 2,
            'total_cost'      => 70.00,
            'status'          => 'cancelada',
        ]);
    }
}
