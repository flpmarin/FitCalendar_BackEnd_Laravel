<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\GroupInterest;
use App\Models\Sport;
use App\Models\User;

class GroupInterestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupInterest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'sport_id' => Sport::factory(),
            'desired_time' => fake()->word(),
            'location_pref' => fake()->word(),
            'is_online_pref' => fake()->boolean(),
            'notes' => fake()->text(),
        ];
    }
}
