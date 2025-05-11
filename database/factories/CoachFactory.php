<?php

namespace Database\Factories;

use App\Models\Coach;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoachFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coach::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'description' => fake()->text(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'coach_type' => fake()->randomElement(['Individual', 'Club']),
            'verified' => fake()->boolean(),
            'organization_id' => Organization::factory(),
            'payment_info' => fake()->word(),
        ];
    }
}
