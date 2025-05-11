<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'user',
        ]);
        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertStatus(401);
    }

    public function test_users_can_logout_via_api(): void
    {
        $user = User::factory()->create();

        // Autenticamos con Sanctum directamente para API
        Sanctum::actingAs($user);

        // Añadimos encabezado Accept:application/json para indicar que queremos respuesta JSON
        $response = $this->withHeader('Accept', 'application/json')
            ->post('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out successfully']);

        // Verificar que el token ha sido eliminado
        $this->assertCount(0, $user->tokens);
    }

    // Omitimos el test de sesión hasta que implementemos la parte web
    public function test_users_can_logout_via_session(): void
    {
        $this->markTestSkipped('El logout basado en sesiones aún no está implementado.');

        $user = User::factory()->create();

        // Iniciamos sesión
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(204); // No Content
        $this->assertGuest();
    }
}
