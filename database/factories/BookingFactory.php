<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Coach;
use App\Models\AvailabilitySlot;
use App\Models\SpecificAvailability;
use App\Models\TrainingClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'coach_id' => Coach::factory(),
            'availability_slot_id' => AvailabilitySlot::factory(),
            'specific_availability_id' => SpecificAvailability::factory(),
            'class_id' => null, // O TrainingClass::factory() si lo necesitas
            'type' => fake()->randomElement(['Personal', 'Group']),
            'session_at' => fake()->dateTime(),
            'session_duration_minutes' => fake()->numberBetween(30, 120), // Limitado a valores razonables
            'status' => fake()->randomElement(['Pending', 'Confirmed', 'Completed', 'Cancelled']),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'platform_fee' => fake()->randomFloat(2, 1, 100),
            'currency' => fake()->randomElement(['USD', 'EUR', 'MXN']),
            'payment_status' => 'Pendiente',
            'cancelled_at' => null,
            'cancelled_reason' => null,
        ];
    }

    // Estados adicionales que se pueden necesitar
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Cancelled',
                'cancelled_at' => fake()->dateTime(),
                'cancelled_reason' => fake()->sentence(),
            ];
        });
    }

    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => 'Completado',
            ];
        });
    }
}
