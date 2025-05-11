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
            Coach::factory()->create([
                'user_id' => $user->id,
                'organization_id' => rand(0, 1) ? $organizations->random()->id : null, // 50% probabilidad de pertenecer a una organización
            ]);
        }
    }
}
