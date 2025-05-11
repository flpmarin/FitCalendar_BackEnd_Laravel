<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'email_verified_at' => fake()->dateTime(),
            'password' => fake()->password(),
            'role' => fake()->randomElement(["Student","Coach","Admin"]),
            'language' => fake()->word(),
            'profile_picture_url' => fake()->word(),
            'stripe_customer_id' => fake()->word(),
            'stripe_account_id' => fake()->word(),
            'remember_token' => fake()->uuid(),
        ];
    }
}
