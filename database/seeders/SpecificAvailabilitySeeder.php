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

            foreach (range(1, 5) as $i) {
                $date = Carbon::now()->addDays(rand(1, 15));
                $start = '0' . rand(8, 9) . ':00';
                $end = '1' . rand(0, 1) . ':00';

                SpecificAvailability::create([
                    'coach_id' => $coach->id,
                    'sport_id' => $sports->random(),
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $start,
                    'end_time' => $end,
                    'capacity' => rand(1, 5),
                    'is_online' => rand(0, 1),
                    'location' => 'Ubicación ' . Str::random(4),
                    'is_booked' => false,
                ]);
            }
        }
    }
}
