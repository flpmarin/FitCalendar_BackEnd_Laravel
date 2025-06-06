<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpecificAvailability;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user || !$user->coach) {
            return response()->json(['message' => 'Acceso denegado: solo entrenadores'], 403);
        }

        $validator = Validator::make($request->all(), [
            'sport_id'   => 'required|exists:sports,id',
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'is_online'  => 'required|boolean',
            'location'   => 'nullable|string|max:255',
            'capacity'   => 'nullable|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }

        // evitar duplicados exactos
        $exists = SpecificAvailability::where('coach_id', $user->coach->id)
            ->where('sport_id', $request->sport_id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->exists();
        if ($exists) return response()->json(['message' => 'Ya existe una disponibilidad idéntica'], 422);

        $slot = SpecificAvailability::create([
            'coach_id'   => $user->coach->id,
            'sport_id'   => $request->sport_id,
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'is_online'  => $request->is_online,
            'location'   => $request->location,
            'capacity'   => $request->capacity ?? 1,
            'is_booked'  => false,
        ]);

        return response()->json(['message' => 'Disponibilidad creada', 'specific_availability' => $slot], 201);
    }

    // PATCH /specific-availabilities/{id}/book
    public function book(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) return response()->json(['message' => 'No autenticado'], 401);

        $slot = SpecificAvailability::where('id', $id)->where('is_booked', false)->first();
        if (!$slot) return response()->json(['message' => 'No encontrada o ya reservada'], 404);

        $slot->is_booked = true;
        $slot->save();

        return response()->json(['message' => 'Bloqueada', 'specific_availability' => $slot]);
    }
}
