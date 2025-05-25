<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_mark_booking_as_paid()
    {
        // Crear usuario y entrenador
        $user = User::factory()->create();
        $coach = Coach::factory()->create(['user_id' => $user->id]);

        // Crear una reserva para el entrenador
        $booking = Booking::factory()->create([
            'coach_id' => $coach->id,
            'type' => 'Personal', // Cambiado de 'Individual' a 'Personal'
            'payment_status' => 'Pendiente',
        ]);

        // Autenticar como el entrenador
        Sanctum::actingAs($user);

        // Enviar la solicitud para marcar como pagado
        $response = $this->patchJson("/api/bookings/{$booking->id}/mark-as-paid");

        // Verificar respuesta exitosa
        $response->assertStatus(200);

        // Verificar que se actualizó en la base de datos
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'Completado',
        ]);
    }

    public function test_only_coach_can_mark_booking_as_paid()
    {
        // Crear un usuario normal (no entrenador)
        $user = User::factory()->create();

        // Crear una reserva
        $booking = Booking::factory()->create([
            'type' => 'Personal', // Cambiado de 'Individual' a 'Personal'
            'payment_status' => 'Pendiente',
        ]);

        // Autenticar como usuario normal
        Sanctum::actingAs($user);

        // Enviar la solicitud para marcar como pagado
        $response = $this->patchJson("/api/bookings/{$booking->id}/mark-as-paid");

        // Verificar respuesta de error
        $response->assertStatus(403);

        // Verificar que no se actualizó en la base de datos
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'Pendiente',
        ]);
    }
}
