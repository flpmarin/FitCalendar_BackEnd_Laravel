<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Coach;
use App\Models\Sport;
use App\Models\TrainingClass;

class TrainingClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingClass::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'sport_id' => Sport::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'starts_at' => fake()->dateTime(),
            'duration_minutes' => fake()->randomNumber(),
            'location_detail' => fake()->word(),
            'is_online' => fake()->boolean(),
            'price_per_person' => fake()->randomFloat(2, 0, 99999999.99),
            'max_capacity' => fake()->randomNumber(),
            'min_required' => fake()->randomNumber(),
            'enrollment_deadline' => fake()->dateTime(),
            'status' => fake()->randomElement(["Scheduled","ReadyToConfirm","Confirmed","Cancelled","Completed"]),
            'cancelled_at' => fake()->dateTime(),
            'cancelled_reason' => fake()->word(),
        ];
    }
}
