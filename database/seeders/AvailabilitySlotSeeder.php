<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Coach;
use App\Models\CoachSport;
use Illuminate\Database\Seeder;

class AvailabilitySlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los entrenadores
        $coaches = Coach::all();

        // Horas de inicio comunes para horarios (formato de 24 horas)
        $startTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '15:00', '16:00', '17:00', '18:00', '19:00'];

        foreach ($coaches as $coach) {
            // Obtener deportes del entrenador
            $coachSports = CoachSport::where('coach_id', $coach->id)->get();

            if ($coachSports->isEmpty()) {
                continue; // Saltar entrenadores sin deportes asignados
            }

            // Asignar entre 5 y 10 slots de disponibilidad por entrenador
            $numSlots = rand(5, 10);
            $createdSlots = 0;

            // Intentar crear slots hasta alcanzar el número requerido
            $maxAttempts = 30; // Limitamos para evitar bucles infinitos
            $attempts = 0;

            while ($createdSlots < $numSlots && $attempts < $maxAttempts) {
                $attempts++;

                // Seleccionar día de la semana (1 = lunes, 7 = domingo)
                $weekday = rand(1, 6); // De lunes a sábado, excluyendo domingos

                // Seleccionar hora de inicio al azar
                $startTime = $startTimes[array_rand($startTimes)];

                // Generar hora de fin (1 o 2 horas después)
                $duration = rand(1, 2);
                $startHour = (int)substr($startTime, 0, 2);
                $endHour = $startHour + $duration;
                $endTime = str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':00';

                // Seleccionar deporte al azar o null (30% probabilidad)
                $sportId = null;
                if (rand(1, 10) > 3) { // 70% probabilidad de asociar a un deporte
                    $sportId = $coachSports->random()->sport_id;
                }

                // Evitar duplicados
                $exists = AvailabilitySlot::where('coach_id', $coach->id)
                    ->where('weekday', $weekday)
                    ->where('start_time', $startTime)
                    ->where('end_time', $endTime)
                    ->exists();

                if (!$exists) {
                    AvailabilitySlot::create([
                        'coach_id' => $coach->id,
                        'sport_id' => $sportId,
                        'weekday' => $weekday,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_online' => rand(0, 10) > 7, // 30% probabilidad de ser online
                        'location' => rand(0, 10) > 5 ? 'Centro deportivo ' . rand(1, 5) : null, // 50% probabilidad de tener ubicación
                        'capacity' => rand(0, 10) > 7 ? rand(2, 5) : 1, // 30% probabilidad de capacidad múltiple
                    ]);

                    $createdSlots++;
                }
            }
        }
    }
}
