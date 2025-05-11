<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@fitcalendar.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'stripe_customer_id' => 'cus_' . Str::random(14),
                'stripe_account_id' => 'acct_' . Str::random(14),
            ]
        );

        // Verificar si hay suficientes entrenadores
        $coachCount = User::where('role', 'Coach')->count();
        if ($coachCount < 5) {
            $numCoachesToCreate = 5 - $coachCount;
            User::factory($numCoachesToCreate)->create([
                'role' => 'Coach',
                'stripe_customer_id' => fn() => 'cus_' . Str::random(14),
                'stripe_account_id' => fn() => 'acct_' . Str::random(14),
            ]);
        }

        // Verificar si hay suficientes estudiantes
        $studentCount = User::where('role', 'Student')->count();
        if ($studentCount < 10) {
            $numStudentsToCreate = 10 - $studentCount;
            User::factory($numStudentsToCreate)->create([
                'role' => 'Student',
                'stripe_customer_id' => fn() => 'cus_' . Str::random(14),
                'stripe_account_id' => fn() => 'acct_' . Str::random(14),
            ]);
        }
    }
}
