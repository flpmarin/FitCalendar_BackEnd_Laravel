<?php

namespace Database\Factories;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

class SportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sport::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name_es' => fake()->word(),
            'name_en' => fake()->word(),
        ];
    }
}
