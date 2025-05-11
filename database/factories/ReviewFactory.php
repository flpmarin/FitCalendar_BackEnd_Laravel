<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Coach;
use App\Models\Review;
use App\Models\User;

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
