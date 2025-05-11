<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            ['name_es' => 'Fútbol', 'name_en' => 'Football'],
            ['name_es' => 'Baloncesto', 'name_en' => 'Basketball'],
            ['name_es' => 'Tenis', 'name_en' => 'Tennis'],
            ['name_es' => 'Natación', 'name_en' => 'Swimming'],
            ['name_es' => 'Yoga', 'name_en' => 'Yoga'],
            ['name_es' => 'Pilates', 'name_en' => 'Pilates'],
            ['name_es' => 'Boxeo', 'name_en' => 'Boxing'],
            ['name_es' => 'Ciclismo', 'name_en' => 'Cycling'],
            ['name_es' => 'Atletismo', 'name_en' => 'Athletics'],
            ['name_es' => 'Padel', 'name_en' => 'Padel'],
        ];

        foreach ($sports as $sport) {
            Sport::create($sport);
        }
    }
}
