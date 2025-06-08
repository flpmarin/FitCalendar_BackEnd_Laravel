<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\AvailabilitySlot;
use App\Models\SpecificAvailability;
use App\Models\Coach;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Listar todas las reservas del usuario autenticado
     */
    public function index(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $query = Booking::query();
        $user->coach ? $query->where('coach_id', $user->coach->id)
            : $query->where('student_id', $user->id);

        $bookings = $query->with(['coach.user', 'student', 'availabilitySlot', 'specificAvailability'])
            ->orderBy('session_at')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Crear una nueva reserva (slot recurrente o puntual)
     */
    public function store(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $validator = Validator::make($request->all(), [
            'coach_id'                 => 'required|exists:coaches,id',
            'sport_id'                 => 'required|exists:sports,id',
            'session_at'               => 'required|date|after:now',
            'availability_slot_id'     => 'nullable|exists:availability_slots,id',
            'specific_availability_id' => 'nullable|exists:specific_availabilities,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }
        if (!$request->availability_slot_id && !$request->specific_availability_id) {
            return response()->json(['message' => 'Indica availability_slot_id o specific_availability_id'], 422);
        }

        $bookingData = [
            'student_id'               => $user->id,
            'coach_id'                 => $request->coach_id,
            'type'                     => 'Personal',
            'session_at'               => $request->session_at,
            'session_duration_minutes' => 60,
            'status'                   => 'Pending',
        ];

        // Disponibilidad puntual
        if ($request->specific_availability_id) {
            $specific = SpecificAvailability::find($request->specific_availability_id);
            if (!$specific || $specific->is_booked || $specific->coach_id != $request->coach_id) {
                return response()->json(['message' => 'Slot puntual inválido'], 422);
            }
            $session = Carbon::parse($request->session_at);

            // Evita errores por diferencias mínimas entre "10:00" y "10:00:00", al usar Carbon::parse
            if (
                !$session->isSameDay($specific->date) ||
                $session->format('H:i:s') !== Carbon::parse($specific->start_time)->format('H:i:s')
            ) {
                return response()->json(['message' => 'La fecha/hora no corresponde a la disponibilidad seleccionada'], 422);
            }


            $specific->is_booked = true;
            $specific->save();
            $bookingData['specific_availability_id'] = $specific->id;
        }

        // Disponibilidad recurrente
        if ($request->availability_slot_id) {
            $slot = AvailabilitySlot::find($request->availability_slot_id);
            if (!$slot || $slot->coach_id != $request->coach_id) {
                return response()->json(['message' => 'Slot recurrente inválido'], 422);
            }
            $sessionDate = Carbon::parse($request->session_at);
            if ($sessionDate->dayOfWeek !== $slot->weekday) {
                return response()->json(['message' => 'La fecha no coincide con el día del slot'], 422);
            }
            $bookingData['availability_slot_id'] = $slot->id;
        }

        $booking = Booking::create($bookingData);
        return response()->json(['message' => 'Reserva creada', 'booking' => $booking->fresh()], 201);
    }

    /**
     * Mostrar una reserva específica
     */
    public function show(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $query = Booking::where('id', $id);
        $user->coach ? $query->where('coach_id', $user->coach->id)
            : $query->where('student_id', $user->id);

        $booking = $query->with(['coach.user', 'student', 'availabilitySlot', 'specificAvailability'])->first();
        if (!$booking) return response()->json(['message' => 'Reserva no encontrada'], 404);

        return response()->json($booking);
    }

    /**
     * Cancelar una reserva
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $query = Booking::where('id', $id);
        $user->coach ? $query->where('coach_id', $user->coach->id)
            : $query->where('student_id', $user->id);

        $booking = $query->first();
        if (!$booking) return response()->json(['message' => 'Reserva no encontrada'], 404);

        if ($booking->status === 'Cancelled') {
            return response()->json(['message' => 'La reserva ya está cancelada'], 422);
        }
        if (Carbon::parse($booking->session_at)->isPast()) {
            return response()->json(['message' => 'No se puede cancelar una sesión pasada'], 422);
        }

        $validator = Validator::make($request->all(), [
            'cancelled_reason' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }

        $booking->update([
            'status'           => 'Cancelled',
            'cancelled_at'     => Carbon::now(),
            'cancelled_reason' => $request->cancelled_reason,
        ]);

        if ($booking->specific_availability_id) {
            SpecificAvailability::where('id', $booking->specific_availability_id)->update(['is_booked' => false]);
        }

        return response()->json(['message' => 'Reserva cancelada', 'booking' => $booking->fresh()]);
    }

    /**
     * Listar entrenadores con disponibilidad futura
     */
    public function getAvailableCoaches(): JsonResponse
    {
        $coaches = Coach::with(['user', 'sports'])
            ->where(function ($q) {
                $q->whereHas('availabilitySlots')
                    ->orWhereHas('specificAvailabilities', function ($q2) {
                        $q2->where('is_booked', false)->where('date', '>=', Carbon::today());
                    });
            })
            ->get();

        return response()->json($coaches);
    }
}
