<?php

namespace Database\Factories;

use App\Models\Coach;
use App\Models\CoachSport;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoachSportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CoachSport::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'sport_id' => Sport::factory(),
            'specific_price' => fake()->randomFloat(2, 0, 99999999.99),
            'specific_location' => fake()->word(),
            'session_duration_minutes' => fake()->randomNumber(),
            'primary' => fake()->word(),
        ];
    }
}
