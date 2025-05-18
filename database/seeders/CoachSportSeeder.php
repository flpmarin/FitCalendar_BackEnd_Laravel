<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\CoachSport;
use App\Models\Sport;
use Illuminate\Database\Seeder;

class CoachSportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coaches = Coach::all();
        $sports = Sport::all();

        foreach ($coaches as $coach) {
            // Asignar entre 1 y 3 deportes a cada entrenador
            $numSports = rand(1, 3);
            $randomSports = $sports->random($numSports);

            foreach ($randomSports as $sport) {
                // Evitar duplicados
                if (!CoachSport::where('coach_id', $coach->id)
                    ->where('sport_id', $sport->id)
                    ->exists()) {

                    CoachSport::create([
                        'coach_id' => $coach->id,
                        'sport_id' => $sport->id,
                        'specific_price' => rand(25, 100), // Precio entre 25 y 100
                        'specific_location' => rand(0, 1) ? 'Centro deportivo ' . $coach->id : null, // 50% probabilidad de tener ubicación específica
                        'session_duration_minutes' => rand(0, 1) ? 60 : 90, // Sesiones de 60 o 90 minutos
                    ]);
                }
            }
        }
    }
}
