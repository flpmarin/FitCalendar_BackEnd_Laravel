<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'stripe_payment_intent_id' => fake()->word(),
            'amount' => fake()->randomFloat(2, 0, 99999999.99),
            'currency' => fake()->word(),
            'status' => fake()->randomElement(['Pending', 'Completed', 'Refunded', 'Failed']),
        ];
    }
}
