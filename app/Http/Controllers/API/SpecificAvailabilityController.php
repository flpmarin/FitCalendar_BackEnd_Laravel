<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpecificAvailability;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SpecificAvailabilityController extends Controller
{
    // GET /specific-availabilities
    public function index(Request $request): JsonResponse
    {
        $query = SpecificAvailability::query()->where('is_booked', false);

        if ($request->filled('coach_id'))  $query->where('coach_id', $request->coach_id);
        if ($request->filled('sport_id'))  $query->where('sport_id', $request->sport_id);
        if ($request->filled('date'))      $query->where('date', $request->date);
        if ($request->filled('is_online')) $query->where('is_online', filter_var($request->is_online, FILTER_VALIDATE_BOOLEAN));

        $slots = $query->with(['coach.user', 'sport'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json($slots);
    }

    // POST /specific-availabilities
    public function store(Request $request): JsonResponse
    {
        try {
            /** @var User|null $user */
            $user = auth()->user();
            if (!$user || !$user->coach) {
                return response()->json(['message' => 'Acceso denegado: solo entrenadores'], 403);
            }

            $validator = Validator::make($request->all(), [
                'sport_id'   => 'required|exists:sports,id',
                'date'       => 'required|date|after_or_equal:today',
                'start_time' => 'required|string',
                'end_time'   => 'required|string',
                'is_online'  => 'required|boolean',
                'location'   => 'nullable|string|max:255',
                'capacity'   => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
            }

            // Validar formatos de hora válidos
            try {
                $startTime = Carbon::parse($request->start_time)->format('H:i:s');
                $endTime   = Carbon::parse($request->end_time)->format('H:i:s');
                if ($endTime <= $startTime) {
                    return response()->json(['message' => 'La hora de fin debe ser posterior a la de inicio'], 422);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Formato de hora inválido'], 422);
            }

            $exists = SpecificAvailability::where('coach_id', $user->coach->id)
                ->where('sport_id', $request->sport_id)
                ->where('date', $request->date)
                ->where('start_time', $request->start_time)
                ->where('end_time', $request->end_time)
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Ya existe una disponibilidad idéntica'], 422);
            }
            // validar que el coach tenga asignado el deporte para el cual se crea la disponibilidad
            $coachSports = $user->coach->sports()->pluck('sports.id')->toArray();
            if (!in_array($request->sport_id, $coachSports)) {
                return response()->json([
                    'message' => 'No puedes crear disponibilidad para un deporte que no tienes asignado.'
                ], 403);
            }

            $slot = SpecificAvailability::create([
                'coach_id'   => $user->coach->id,
                'sport_id'   => $request->sport_id,
                // Convertir la fecha a UTC y establecer el inicio del día
                'date' => Carbon::createFromFormat('Y-m-d', $request->date, config('app.timezone'))
                    ->startOfDay()
                    ->setTimezone('UTC'),
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'is_online'  => $request->is_online,
                'location'   => $request->location,
                'capacity'   => $request->capacity ?? 1,
                'is_booked'  => false,
            ]);

            return response()->json([
                'message' => 'Disponibilidad creada',
                'specific_availability' => [
                    'id' => $slot->id,
                    'date' => optional($slot->date)->toDateString(),
                    'start_time' => optional($slot->start_time)->format('H:i'),
                    'end_time' => optional($slot->end_time)->format('H:i'),
                ],
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error creando disponibilidad específica', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    // PATCH /specific-availabilities/{id}/book
    public function book(int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $slot = SpecificAvailability::where('id', $id)->where('is_booked', false)->first();
        if (!$slot) return response()->json(['message' => 'No encontrada o ya reservada'], 404);

        $slot->is_booked = true;
        $slot->save();

        return response()->json(['message' => 'Bloqueada', 'specific_availability' => $slot]);
    }

    // cancelar una reservación
    // DELETE /specific-availabilities/{id}/cancel
    public function cancel(int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $slot = SpecificAvailability::where('id', $id)->where('is_booked', true)->first();
        if (!$slot) return response()->json(['message' => 'No encontrada o no reservada'], 404);

        $slot->is_booked = false;
        $slot->save();

        return response()->json(['message' => 'Reservación cancelada', 'specific_availability' => $slot]);
    }

    // Eliminar una disponibilidad específica
    // DELETE /specific-availabilities/{id}
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user || !$user->coach) {
            return response()->json(['message' => 'No autenticado o no es coach'], 401);
        }

        $slot = SpecificAvailability::find($id);
        if (!$slot) return response()->json(['message' => 'Disponibilidad no encontrada'], 404);

        if ($slot->coach_id !== $user->coach->id) {
            return response()->json(['message' => 'No autorizado para eliminar esta disponibilidad'], 403);
        }

        $slot->delete();

        return response()->json(['message' => 'Disponibilidad eliminada']);
    }
}
