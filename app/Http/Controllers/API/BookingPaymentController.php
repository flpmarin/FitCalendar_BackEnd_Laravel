<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
    /**
     * Marcar una reserva como pagada
     */
    public function markAsPaid(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Verificar que el usuario esté autenticado
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Verificar que el usuario sea un entrenador
        $coach = $user->coach;
        if (!$coach) {
            return response()->json([
                'message' => 'Solo los entrenadores pueden marcar pagos'
            ], 403);
        }

        // Buscar la reserva y verificar que pertenezca a este entrenador
        $booking = Booking::where('id', $id)
            ->where('coach_id', $coach->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Reserva no encontrada o no pertenece a este entrenador'
            ], 404);
        }

        // Verificar que no esté ya pagada
        if ($booking->isPaid()) {
            return response()->json([
                'message' => 'Esta reserva ya está marcada como pagada'
            ], 422);
        }

        // Marcar como pagada
        $booking->markAsPaid();

        return response()->json([
            'message' => 'Pago marcado como completado',
            'booking' => $booking->fresh()
        ]);
    }
}
