<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Sport;
use App\Models\TrainingClass;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrainingClassSeeder extends Seeder
{
    public function run(): void
    {
        $coaches = Coach::all();
        $sports = Sport::all();

        foreach ($coaches as $coach) {
            // Crear entre 2 y 5 clases por entrenador
            $numClasses = rand(2, 5);

            for ($i = 0; $i < $numClasses; $i++) {
                $startDate = Carbon::now()->addDays(rand(1, 30));

                TrainingClass::factory()->create([
                    'coach_id' => $coach->id,
                    'sport_id' => $sports->random()->id,
                    'starts_at' => $startDate,
                    'price_per_person' => rand(20, 100),
                    'max_capacity' => rand(5, 15),
                    'min_required' => rand(2, 4),
                    'enrollment_deadline' => $startDate->copy()->subDays(1),
                ]);
            }
        }
    }
}
