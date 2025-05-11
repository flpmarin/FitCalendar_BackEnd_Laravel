<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Coach;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::factory(),
            'student_id' => User::factory(),
            'booking_id' => Booking::factory(),
            'rating' => fake()->randomDigitNotNull(),
            'comment' => fake()->text(),
        ];
    }
}
