<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class CoachSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios con rol de entrenador
        $coachUsers = User::where('role', 'Coach')->get();
        $organizations = Organization::all();

        foreach ($coachUsers as $user) {
            Coach::updateOrCreate(
                ['user_id' => $user->id],                           // evita duplicados
                ['organization_id' => rand(0, 1)                    // 50 % con organización
                    ? $organizations->random()->id
                    : null]
            );
        }
    }
}
