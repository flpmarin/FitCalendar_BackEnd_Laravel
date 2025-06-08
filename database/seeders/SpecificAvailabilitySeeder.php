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
                continue; // ⚠️ Saltar coach sin deportes
            }

            foreach (range(1, 5) as $i) {
                $date = Carbon::now()->addDays(rand(1, 15));

                // Horas válidas y bien formateadas
                $startHour = rand(8, 16); // entre 08:00 y 16:00
                $endHour   = $startHour + 1;

                $start = Carbon::createFromTime($startHour, 0, 0)->format('H:i:s');
                $end   = Carbon::createFromTime($endHour, 0, 0)->format('H:i:s');

                SpecificAvailability::create([
                    'coach_id'   => $coach->id,
                    'sport_id'   => $sports->random(),
                    'date'       => $date->format('Y-m-d'),
                    'start_time' => $start,
                    'end_time'   => $end,
                    'capacity'   => rand(1, 4),
                    'is_online'  => (bool) rand(0, 1),
                    'location'   => 'Ubicación ' . Str::random(4),
                    'is_booked'  => false,
                ]);
            }
        }
    }
}
