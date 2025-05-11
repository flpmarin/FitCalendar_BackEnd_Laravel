<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $admins = User::where('role', 'Admin')->get();

        foreach ($admins as $admin) {
            Organization::factory()
                ->create([
                    'owner_id' => $admin->id,
                ]);
        }

        // Crear algunas organizaciones adicionales
        Organization::factory(3)->create([
            'owner_id' => User::where('role', 'Coach')->inRandomOrder()->first()->id,
        ]);
    }
}
