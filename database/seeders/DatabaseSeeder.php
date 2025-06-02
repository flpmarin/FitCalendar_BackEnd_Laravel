<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrganizationSeeder::class,
            SportSeeder::class,
            CoachSeeder::class,
            CoachSportSeeder::class,
            TrainingClassSeeder::class,
        ]);
    }
}
