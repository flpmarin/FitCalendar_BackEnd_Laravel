<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AvailabilitySlot;
use App\Models\Coach;
use App\Models\Sport;

class AvailabilitySlotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AvailabilitySlot::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'sport_id' => Sport::factory(),
            'weekday' => fake()->numberBetween(-8, 8),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'is_online' => fake()->boolean(),
            'location' => fake()->word(),
            'capacity' => fake()->randomNumber(),
            'unique' => fake()->word(),
        ];
    }
}
