<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Crear organizaciones para cada Admin
        $admins = User::where('role', 'Admin')->get();

        foreach ($admins as $admin) {
            Organization::factory()->create([
                'owner_id' => $admin->id,
            ]);
        }

        // Crear 3 organizaciones adicionales con dueños Coach (si existen)
        $coachOwners = User::where('role', 'Coach')->inRandomOrder()->take(3)->get();

        foreach ($coachOwners as $coach) {
            Organization::factory()->create([
                'owner_id' => $coach->id,
            ]);
        }
    }
}
