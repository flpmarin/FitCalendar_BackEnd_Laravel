<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\TrainingClass; //
use App\Models\Coach;
use App\Models\SpecificAvailability;
use App\Models\User;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'coach_id' => Coach::factory(),
            'availability_slot_id' => AvailabilitySlot::factory(),
            'specific_availability_id' => SpecificAvailability::factory(),
            'class_id' => TrainingClass::factory(), //
            'type' => fake()->randomElement(["Personal","Group"]),
            'session_at' => fake()->dateTime(),
            'session_duration_minutes' => fake()->randomNumber(),
            'status' => fake()->randomElement(["Pending","Confirmed","Cancelled","Completed","Rejected"]),
            'total_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'platform_fee' => fake()->randomFloat(2, 0, 99999999.99),
            'currency' => fake()->word(),
            'cancelled_at' => fake()->dateTime(),
            'cancelled_reason' => fake()->word(),
        ];
    }
}
