<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Sport;

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
