<?php

namespace Database\Seeders;

use App\Models\Extra;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $maria  = User::where('email', 'maria@alquiler.com')->first();
        $carlos = User::where('email', 'carlos@alquiler.com')->first();
        $laura  = User::where('email', 'laura@alquiler.com')->first();

        $gps    = Extra::where('name', 'GPS')->first();
        $seguro = Extra::where('name', 'Seguro de ocupantes')->first();
        $asiento = Extra::where('name', 'Asiento para bebés')->first();
        $asist  = Extra::where('name', 'Asistencia en carretera')->first();
        $wifi   = Extra::where('name', 'WiFi portátil')->first();
        $porta  = Extra::where('name', 'Portaequipajes')->first();

        // ── Historial (pasadas) ────────────────────────────────────────────

        $corolla = Vehicle::where('plate', 'B001IN')->first();
        $r1 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $corolla->id,
            'start_date' => now()->subDays(3), 'end_date' => now()->addDays(2),
            'passenger_count' => 3, 'total_cost' => 280.00, 'status' => 'confirmada',
            'delivery_plate' => $corolla->plate, 'delivery_mileage' => $corolla->current_mileage,
            'delivery_fuel_level' => $corolla->current_fuel_level,
        ]);
        $r1->extras()->attach($gps->id, ['quantity' => 1]);
        $r1->extras()->attach($seguro->id, ['quantity' => 1]);

        $rav4 = Vehicle::where('plate', 'C001SV')->first();
        $r2 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $rav4->id,
            'start_date' => now()->subDay(), 'end_date' => now()->addDays(4),
            'passenger_count' => 4, 'total_cost' => 450.00, 'status' => 'confirmada',
            'delivery_plate' => $rav4->plate, 'delivery_mileage' => $rav4->current_mileage,
            'delivery_fuel_level' => $rav4->current_fuel_level,
        ]);
        $r2->extras()->attach($gps->id, ['quantity' => 1]);
        $r2->extras()->attach($asiento->id, ['quantity' => 1]);
        $r2->extras()->attach($asist->id, ['quantity' => 1]);

        $civic = Vehicle::where('plate', 'B002IN')->first();
        Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $civic->id,
            'start_date' => now()->addDays(3), 'end_date' => now()->addDays(6),
            'passenger_count' => 2, 'total_cost' => 180.00, 'status' => 'pendiente',
        ]);

        $hilux = Vehicle::where('plate', 'E001PK')->first();
        $r4 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $hilux->id,
            'start_date' => now()->subDays(15), 'end_date' => now()->subDays(10),
            'passenger_count' => 2, 'total_cost' => 462.00, 'status' => 'completada',
            'delivery_plate' => $hilux->plate, 'delivery_mileage' => $hilux->current_mileage,
            'delivery_fuel_level' => $hilux->current_fuel_level,
        ]);
        $r4->extras()->attach($asist->id, ['quantity' => 1]);

        $bmw = Vehicle::where('plate', 'D001PM')->first();
        $r5 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $bmw->id,
            'start_date' => now()->subDays(20), 'end_date' => now()->subDays(17),
            'passenger_count' => 1, 'total_cost' => 465.00, 'status' => 'completada',
            'delivery_plate' => $bmw->plate, 'delivery_mileage' => $bmw->current_mileage,
            'delivery_fuel_level' => $bmw->current_fuel_level,
        ]);
        $r5->extras()->attach($wifi->id, ['quantity' => 1]);

        $yaris = Vehicle::where('plate', 'A001EC')->first();
        Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $yaris->id,
            'start_date' => now()->subDays(5), 'end_date' => now()->subDays(3),
            'passenger_count' => 2, 'total_cost' => 70.00, 'status' => 'cancelada',
        ]);

        // ── Próximas (vehículos no disponibles en fechas cercanas) ──────────

        // A011EC - Chevrolet Onix - reservada próximos días
        $onix = Vehicle::where('plate', 'A011EC')->first();
        $r7 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $onix->id,
            'start_date' => now()->addDay(), 'end_date' => now()->addDays(5),
            'passenger_count' => 2, 'total_cost' => 155.00, 'status' => 'confirmada',
            'delivery_plate' => $onix->plate, 'delivery_mileage' => $onix->current_mileage,
            'delivery_fuel_level' => $onix->current_fuel_level,
        ]);
        $r7->extras()->attach($gps->id, ['quantity' => 1]);

        // A021EC - Kia Rio 2023 - pendiente próximos días
        $rio = Vehicle::where('plate', 'A021EC')->first();
        Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $rio->id,
            'start_date' => now()->addDays(2), 'end_date' => now()->addDays(5),
            'passenger_count' => 3, 'total_cost' => 102.00, 'status' => 'pendiente',
        ]);

        // B011IN - Nissan Altima - confirmada próxima semana
        $altima = Vehicle::where('plate', 'B011IN')->first();
        $r9 = Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $altima->id,
            'start_date' => now()->addDays(4), 'end_date' => now()->addDays(8),
            'passenger_count' => 2, 'total_cost' => 228.00, 'status' => 'confirmada',
            'delivery_plate' => $altima->plate, 'delivery_mileage' => $altima->current_mileage,
            'delivery_fuel_level' => $altima->current_fuel_level,
        ]);
        $r9->extras()->attach($wifi->id, ['quantity' => 1]);
        $r9->extras()->attach($seguro->id, ['quantity' => 1]);

        // B025IN - Honda Civic 2023 - confirmada próximos días
        $civic23 = Vehicle::where('plate', 'B025IN')->first();
        $r10 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $civic23->id,
            'start_date' => now()->addDays(1), 'end_date' => now()->addDays(4),
            'passenger_count' => 4, 'total_cost' => 171.00, 'status' => 'confirmada',
            'delivery_plate' => $civic23->plate, 'delivery_mileage' => $civic23->current_mileage,
            'delivery_fuel_level' => $civic23->current_fuel_level,
        ]);
        $r10->extras()->attach($asist->id, ['quantity' => 1]);

        // C011SV - Toyota Fortuner - confirmada próxima semana
        $fortuner = Vehicle::where('plate', 'C011SV')->first();
        $r11 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $fortuner->id,
            'start_date' => now()->addDays(3), 'end_date' => now()->addDays(7),
            'passenger_count' => 6, 'total_cost' => 408.00, 'status' => 'confirmada',
            'delivery_plate' => $fortuner->plate, 'delivery_mileage' => $fortuner->current_mileage,
            'delivery_fuel_level' => $fortuner->current_fuel_level,
        ]);
        $r11->extras()->attach($gps->id, ['quantity' => 1]);
        $r11->extras()->attach($asiento->id, ['quantity' => 2]);

        // C024SV - Nissan X-Trail 2023 - pendiente próximos días
        $xtrail = Vehicle::where('plate', 'C024SV')->first();
        Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $xtrail->id,
            'start_date' => now()->addDays(2), 'end_date' => now()->addDays(6),
            'passenger_count' => 5, 'total_cost' => 320.00, 'status' => 'pendiente',
        ]);

        // D009PM - BMW Serie 5 - confirmada próxima semana
        $bmw5 = Vehicle::where('plate', 'D009PM')->first();
        $r13 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $bmw5->id,
            'start_date' => now()->addDays(6), 'end_date' => now()->addDays(9),
            'passenger_count' => 2, 'total_cost' => 640.00, 'status' => 'confirmada',
            'delivery_plate' => $bmw5->plate, 'delivery_mileage' => $bmw5->current_mileage,
            'delivery_fuel_level' => $bmw5->current_fuel_level,
        ]);
        $r13->extras()->attach($wifi->id, ['quantity' => 1]);
        $r13->extras()->attach($seguro->id, ['quantity' => 1]);

        // D014PM - Tesla Model Y - confirmada próximos días
        $tesla = Vehicle::where('plate', 'D014PM')->first();
        $r14 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $tesla->id,
            'start_date' => now()->addDays(3), 'end_date' => now()->addDays(7),
            'passenger_count' => 3, 'total_cost' => 796.00, 'status' => 'confirmada',
            'delivery_plate' => $tesla->plate, 'delivery_mileage' => $tesla->current_mileage,
            'delivery_fuel_level' => $tesla->current_fuel_level,
        ]);
        $r14->extras()->attach($asist->id, ['quantity' => 1]);

        // E007PK - Toyota Hilux 2023 - confirmada próximos días
        $hilux23 = Vehicle::where('plate', 'E007PK')->first();
        $r15 = Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $hilux23->id,
            'start_date' => now()->addDays(1), 'end_date' => now()->addDays(4),
            'passenger_count' => 4, 'total_cost' => 300.00, 'status' => 'confirmada',
            'delivery_plate' => $hilux23->plate, 'delivery_mileage' => $hilux23->current_mileage,
            'delivery_fuel_level' => $hilux23->current_fuel_level,
        ]);
        $r15->extras()->attach($gps->id, ['quantity' => 1]);
        $r15->extras()->attach($porta->id, ['quantity' => 1]);

        // F002VN - Toyota Sienna 2023 - confirmada próxima semana
        $sienna = Vehicle::where('plate', 'F002VN')->first();
        $r16 = Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $sienna->id,
            'start_date' => now()->addDays(7), 'end_date' => now()->addDays(11),
            'passenger_count' => 7, 'total_cost' => 512.00, 'status' => 'confirmada',
            'delivery_plate' => $sienna->plate, 'delivery_mileage' => $sienna->current_mileage,
            'delivery_fuel_level' => $sienna->current_fuel_level,
        ]);
        $r16->extras()->attach($asiento->id, ['quantity' => 2]);
        $r16->extras()->attach($wifi->id, ['quantity' => 1]);

        // A033EC - Kia Picanto 2023 - pendiente próximos días
        $picanto = Vehicle::where('plate', 'A033EC')->first();
        Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $picanto->id,
            'start_date' => now()->addDays(2), 'end_date' => now()->addDays(6),
            'passenger_count' => 2, 'total_cost' => 112.00, 'status' => 'pendiente',
        ]);

        // B046IN - Toyota Corolla 2024 - confirmada próxima semana
        $corolla24 = Vehicle::where('plate', 'B046IN')->first();
        $r18 = Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $corolla24->id,
            'start_date' => now()->addDays(5), 'end_date' => now()->addDays(9),
            'passenger_count' => 3, 'total_cost' => 240.00, 'status' => 'confirmada',
            'delivery_plate' => $corolla24->plate, 'delivery_mileage' => $corolla24->current_mileage,
            'delivery_fuel_level' => $corolla24->current_fuel_level,
        ]);
        $r18->extras()->attach($gps->id, ['quantity' => 1]);

        // C030SV - Hyundai Tucson 2023 - pendiente próximos días
        $tucson = Vehicle::where('plate', 'C030SV')->first();
        Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $tucson->id,
            'start_date' => now()->addDays(1), 'end_date' => now()->addDays(4),
            'passenger_count' => 4, 'total_cost' => 312.00, 'status' => 'pendiente',
        ]);

        // F009VN - Kia Carnival 2023 - confirmada próxima semana
        $carnival = Vehicle::where('plate', 'F009VN')->first();
        $r20 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $carnival->id,
            'start_date' => now()->addDays(5), 'end_date' => now()->addDays(9),
            'passenger_count' => 7, 'total_cost' => 492.00, 'status' => 'confirmada',
            'delivery_plate' => $carnival->plate, 'delivery_mileage' => $carnival->current_mileage,
            'delivery_fuel_level' => $carnival->current_fuel_level,
        ]);
        $r20->extras()->attach($asiento->id, ['quantity' => 1]);
        $r20->extras()->attach($wifi->id, ['quantity' => 1]);
        $r20->extras()->attach($gps->id, ['quantity' => 1]);

        // E011PK - RAM 1500 - confirmada en 10 días
        $ram = Vehicle::where('plate', 'E011PK')->first();
        $r21 = Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $ram->id,
            'start_date' => now()->addDays(10), 'end_date' => now()->addDays(14),
            'passenger_count' => 3, 'total_cost' => 456.00, 'status' => 'confirmada',
            'delivery_plate' => $ram->plate, 'delivery_mileage' => $ram->current_mileage,
            'delivery_fuel_level' => $ram->current_fuel_level,
        ]);
        $r21->extras()->attach($porta->id, ['quantity' => 1]);

        // D016PM - Volvo XC60 - pendiente próxima semana
        $volvo = Vehicle::where('plate', 'D016PM')->first();
        Reservation::create([
            'user_id' => $maria->id, 'vehicle_id' => $volvo->id,
            'start_date' => now()->addDays(8), 'end_date' => now()->addDays(12),
            'passenger_count' => 2, 'total_cost' => 728.00, 'status' => 'pendiente',
        ]);

        // A046EC - Toyota Yaris 2023 - confirmada mañana
        $yaris23 = Vehicle::where('plate', 'A046EC')->first();
        $r23 = Reservation::create([
            'user_id' => $carlos->id, 'vehicle_id' => $yaris23->id,
            'start_date' => now()->addDay(), 'end_date' => now()->addDays(3),
            'passenger_count' => 2, 'total_cost' => 72.00, 'status' => 'confirmada',
            'delivery_plate' => $yaris23->plate, 'delivery_mileage' => $yaris23->current_mileage,
            'delivery_fuel_level' => $yaris23->current_fuel_level,
        ]);
        $r23->extras()->attach($wifi->id, ['quantity' => 1]);

        // C039SV - Land Cruiser - confirmada en 2 semanas
        $lc = Vehicle::where('plate', 'C039SV')->first();
        $r24 = Reservation::create([
            'user_id' => $laura->id, 'vehicle_id' => $lc->id,
            'start_date' => now()->addDays(12), 'end_date' => now()->addDays(16),
            'passenger_count' => 6, 'total_cost' => 556.00, 'status' => 'confirmada',
            'delivery_plate' => $lc->plate, 'delivery_mileage' => $lc->current_mileage,
            'delivery_fuel_level' => $lc->current_fuel_level,
        ]);
        $r24->extras()->attach($gps->id, ['quantity' => 1]);
        $r24->extras()->attach($asist->id, ['quantity' => 1]);
        $r24->extras()->attach($asiento->id, ['quantity' => 1]);
    }
}
