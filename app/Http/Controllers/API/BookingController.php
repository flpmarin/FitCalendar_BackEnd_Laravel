<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SpecificAvailability;
use App\Models\Coach;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $query = Booking::query();
        $user->coach
            ? $query->where('coach_id', $user->coach->id)
            : $query->where('student_id', $user->id);

        $bookings = $query->with(['coach.user', 'student', 'specificAvailability'])
            ->orderBy('session_at')
            ->get();

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $validator = Validator::make($request->all(), [
            'coach_id'                 => 'required|exists:coaches,id',
            'sport_id'                 => 'required|exists:sports,id',
            'session_at'               => 'required|date|after:now',
            'specific_availability_id' => 'required|exists:specific_availabilities,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }

        $specific = SpecificAvailability::find($request->specific_availability_id);

        if (!$specific || $specific->is_booked || $specific->coach_id != $request->coach_id) {
            return response()->json(['message' => 'Slot puntual inválido'], 422);
        }

        $sessionUtc = Carbon::parse($request->session_at)->setTimezone('UTC');
        $slotDateTimeUtc = Carbon::parse($specific->date->format('Y-m-d') . ' ' . $specific->start_time->format('H:i:s'), 'UTC');

        if (!$sessionUtc->equalTo($slotDateTimeUtc)) {
            return response()->json([
                'message' => 'La fecha/hora no corresponde a la disponibilidad seleccionada'
            ], 422);
        }

        $specific->update(['is_booked' => true]);

        $booking = Booking::create([
            'student_id'               => $user->id,
            'coach_id'                 => $specific->coach_id,
            'type'                     => 'Personal',
            'session_at'               => $request->session_at,
            'session_duration_minutes' => 60,
            'status'                   => 'Pending',
            'specific_availability_id' => $specific->id,
        ]);

        return response()->json(['message' => 'Reserva creada', 'booking' => $booking->fresh()], 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $query = Booking::where('id', $id);
        $user->coach
            ? $query->where('coach_id', $user->coach->id)
            : $query->where('student_id', $user->id);

        $booking = $query->with(['coach.user', 'student', 'specificAvailability'])->first();
        if (!$booking) return response()->json(['message' => 'Reserva no encontrada'], 404);

        return response()->json($booking);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $query = Booking::where('id', $id);
        $user->coach
            ? $query->where('coach_id', $user->coach->id)
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

    public function getAvailableCoaches(): JsonResponse
    {
        $coaches = Coach::with(['user', 'sports'])
            ->whereHas('specificAvailabilities', function ($q) {
                $q->where('is_booked', false)->where('date', '>=', Carbon::today());
            })
            ->get();

        return response()->json($coaches);
    }
}
