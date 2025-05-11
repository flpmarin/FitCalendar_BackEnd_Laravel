<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\User;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'owner_id' => User::factory(),
        ];
    }
}
