<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\AvailabilitySlot;
use App\Models\Coach;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Listar todas las reservas del usuario actual
     */
    public function index(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $query = Booking::query();

        // Si es entrenador, mostrar sus clases
        if ($user->coach) {
            $query->where('coach_id', $user->coach->id);
        } else {
            // Si es estudiante, mostrar sus reservas
            $query->where('student_id', $user->id);
        }

        $bookings = $query->with(['coach.user', 'student', 'availabilitySlot'])
            ->orderBy('session_at')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'coach_id' => 'required|exists:coaches,id',
            'sport_id' => 'required|exists:sports,id',
            'session_at' => 'required|date|after:now',
            'availability_slot_id' => 'required|exists:availability_slots,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que el slot pertenece al entrenador
        $slot = AvailabilitySlot::find($request->availability_slot_id);
        if (!$slot || $slot->coach_id != $request->coach_id) {
            return response()->json([
                'message' => 'La franja de disponibilidad no es válida para este entrenador'
            ], 422);
        }

        // Verificar que la fecha corresponde al día de la semana del slot
        $sessionDate = Carbon::parse($request->session_at);
        $weekday = $sessionDate->dayOfWeek; // 0 (domingo) a 6 (sábado)

        if ($weekday != $slot->weekday) {
            return response()->json([
                'message' => 'La fecha seleccionada no corresponde al día de la semana de la franja horaria'
            ], 422);
        }

        // Valores fijos para simplificar
        $sessionDuration = 60; // 60 minutos por defecto
        $totalAmount = 30.00; // 30€ por defecto

        // Crear la reserva
        $booking = new Booking([
            'student_id' => $user->id,
            'coach_id' => $request->coach_id,
            'availability_slot_id' => $request->availability_slot_id,
            'type' => 'Individual', // Valor fijo para el PoC
            'session_at' => $request->session_at,
            'session_duration_minutes' => $sessionDuration,
            'status' => 'Pendiente',
            'total_amount' => $totalAmount,
            'currency' => 'EUR', // Valor por defecto
            'payment_status' => 'Pendiente',
        ]);

        $booking->save();

        return response()->json([
            'message' => 'Reserva creada correctamente',
            'booking' => $booking->fresh()
        ], 201);
    }

    /**
     * Obtener una reserva específica
     */
    public function show(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $query = Booking::where('id', $id);

        // Si es entrenador, solo ver sus clases
        if ($user->coach) {
            $query->where('coach_id', $user->coach->id);
        } else {
            // Si es estudiante, solo ver sus reservas
            $query->where('student_id', $user->id);
        }

        $booking = $query->with(['coach.user', 'student', 'availabilitySlot'])
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        return response()->json($booking);
    }

    /**
     * Cancelar una reserva
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $query = Booking::where('id', $id);

        // Si es entrenador, solo cancelar sus clases
        if ($user->coach) {
            $query->where('coach_id', $user->coach->id);
        } else {
            // Si es estudiante, solo cancelar sus reservas
            $query->where('student_id', $user->id);
        }

        $booking = $query->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        // Verificar que no esté ya cancelada
        if ($booking->status === 'Cancelada') {
            return response()->json([
                'message' => 'Esta reserva ya está cancelada'
            ], 422);
        }

        // Verificar que la sesión no haya pasado ya
        if (Carbon::parse($booking->session_at)->isPast()) {
            return response()->json([
                'message' => 'No se puede cancelar una reserva para una sesión que ya pasó'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'cancelled_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking->status = 'Cancelada';
        $booking->cancelled_at = Carbon::now();
        $booking->cancelled_reason = $request->cancelled_reason;
        $booking->save();

        return response()->json([
            'message' => 'Reserva cancelada correctamente',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Listar entrenadores disponibles
     */
    public function getAvailableCoaches(): JsonResponse
    {
        $coaches = Coach::with(['user', 'sports'])
            ->whereHas('availabilitySlots')
            ->get();

        return response()->json($coaches);
    }
}
