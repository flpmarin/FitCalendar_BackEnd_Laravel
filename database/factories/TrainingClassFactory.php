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
            'duration_minutes' => fake()->numberBetween(30, 180), // Valores razonables entre 30 min y 3 horas
            'location_detail' => fake()->word(),
            'is_online' => fake()->boolean(),
            'price_per_person' => fake()->randomFloat(2, 10, 999.99), // Precios más realistas
            'max_capacity' => fake()->numberBetween(5, 30), // Capacidad razonable
            'min_required' => fake()->numberBetween(1, 4), // Mínimo requerido razonable
            'enrollment_deadline' => fake()->dateTime(),
            'status' => fake()->randomElement(["Scheduled","ReadyToConfirm","Confirmed","Cancelled","Completed"]),
            'cancelled_at' => fake()->dateTime(),
            'cancelled_reason' => fake()->word(),
        ];
    }
}
