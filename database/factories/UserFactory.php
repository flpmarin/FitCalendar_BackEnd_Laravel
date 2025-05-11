<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
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
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['Student', 'Coach', 'Admin']),
            'language' => 'es',
            'profile_picture_url' => null,
            'stripe_customer_id' => null,
            'stripe_account_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model to have Stripe IDs.
     */
    public function withStripeIds(): static
    {
        static $customerCounter = 1;
        static $accountCounter = 1;

        return $this->state(function (array $attributes) use (&$customerCounter, &$accountCounter) {
            return [
                'stripe_customer_id' => 'cus_' . str_pad($customerCounter++, 14, '0', STR_PAD_LEFT),
                'stripe_account_id' => 'acct_' . str_pad($accountCounter++, 14, '0', STR_PAD_LEFT),
            ];
        });
    }
}
