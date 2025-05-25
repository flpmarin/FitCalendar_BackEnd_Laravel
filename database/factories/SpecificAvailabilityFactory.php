<?php

namespace Database\Factories;

use App\Models\Coach;
use App\Models\SpecificAvailability;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecificAvailabilityFactory extends Factory
{
    protected $model = SpecificAvailability::class;

    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'sport_id' => Sport::factory(),
            'start_at' => fake()->dateTime(),
            'end_at' => fake()->dateTime(),
            'availability_type' => 'Blocked',
            'capacity' => fake()->numberBetween(1, 20),
            'is_online' => fake()->boolean(),
            'location' => fake()->word(),
            'reason' => fake()->word(),
        ];
    }
}
