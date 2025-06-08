<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\CoachSport;
use App\Models\SpecificAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SpecificAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $coaches = Coach::all();

        foreach ($coaches as $coach) {
            $sports = CoachSport::where('coach_id', $coach->id)->pluck('sport_id');

            if ($sports->isEmpty()) {
                continue; // Saltar si el coach no tiene deportes asignados
            }

            foreach (range(1, 5) as $i) {
                $date = Carbon::now()->addDays(rand(1, 15));

                // Horas en bloques válidos (ej: 08:00:00 a 09:00:00)
                $startTime = Carbon::createFromTime(rand(8, 16), 0, 0)->format('H:i:s');
                $endTime = Carbon::createFromTime(Carbon::parse($startTime)->hour + 1, 0, 0)->format('H:i:s');

                SpecificAvailability::create([
                    'coach_id' => $coach->id,
                    'sport_id' => $sports->random(),
                    'date' => $date->copy()->startOfDay()->setTimezone('UTC'), // UTC explícito
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'capacity' => rand(1, 5),
                    'is_online' => (bool) rand(0, 1),
                    'location' => 'Ubicación ' . Str::random(4),
                    'is_booked' => false,
                ]);
            }
        }
    }
}
