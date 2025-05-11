<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Coach;
use App\Models\SpecificAvailability;
use App\Models\Sport;

class SpecificAvailabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecificAvailability::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'sport_id' => Sport::factory(),
            'start_at' => fake()->dateTime(),
            'end_at' => fake()->dateTime(),
            'availability_type' => fake()->randomElement(["Available","Blocked"]),
            'capacity' => fake()->randomNumber(),
            'is_online' => fake()->boolean(),
            'location' => fake()->word(),
            'reason' => fake()->word(),
        ];
    }
}
