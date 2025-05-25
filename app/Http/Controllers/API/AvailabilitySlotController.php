<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailabilitySlotController extends Controller
{
    /**
     * Listar franjas de disponibilidad del entrenador actual
     */
    public function index(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'Acceso denegado: solo entrenadores pueden acceder a esta función'
            ], 403);
        }

        $availabilitySlots = AvailabilitySlot::where('coach_id', $user->coach->id)
            ->with('sport')
            ->get();

        return response()->json($availabilitySlots);
    }

    /**
     * Crear una nueva franja de disponibilidad
     */
    public function store(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'Acceso denegado: solo entrenadores pueden acceder a esta función'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'sport_id' => 'required|exists:sports,id',
            'weekday' => 'required|integer|between:0,6',  // 0: domingo, 6: sábado
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_online' => 'required|boolean',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $availabilitySlot = new AvailabilitySlot($request->all());
        $availabilitySlot->coach_id = $user->coach->id;
        $availabilitySlot->save();

        return response()->json([
            'message' => 'Franja de disponibilidad creada correctamente',
            'availabilitySlot' => $availabilitySlot
        ], 201);
    }

    /**
     * Obtener una franja de disponibilidad específica
     */
    public function show(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'Acceso denegado: solo entrenadores pueden acceder a esta función'
            ], 403);
        }

        $availabilitySlot = AvailabilitySlot::where('id', $id)
            ->where('coach_id', $user->coach->id)
            ->with('sport')
            ->first();

        if (!$availabilitySlot) {
            return response()->json([
                'message' => 'Franja de disponibilidad no encontrada'
            ], 404);
        }

        return response()->json($availabilitySlot);
    }

    /**
     * Actualizar una franja de disponibilidad
     */
    public function update(Request $request, int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'Acceso denegado: solo entrenadores pueden acceder a esta función'
            ], 403);
        }

        $availabilitySlot = AvailabilitySlot::where('id', $id)
            ->where('coach_id', $user->coach->id)
            ->first();

        if (!$availabilitySlot) {
            return response()->json([
                'message' => 'Franja de disponibilidad no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'sport_id' => 'sometimes|required|exists:sports,id',
            'weekday' => 'sometimes|required|integer|between:0,6',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'is_online' => 'sometimes|required|boolean',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $availabilitySlot->update($request->all());

        return response()->json([
            'message' => 'Franja de disponibilidad actualizada correctamente',
            'availabilitySlot' => $availabilitySlot
        ]);
    }

    /**
     * Eliminar una franja de disponibilidad
     */
    public function destroy(int $id): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'Acceso denegado: solo entrenadores pueden acceder a esta función'
            ], 403);
        }

        $availabilitySlot = AvailabilitySlot::where('id', $id)
            ->where('coach_id', $user->coach->id)
            ->first();

        if (!$availabilitySlot) {
            return response()->json([
                'message' => 'Franja de disponibilidad no encontrada'
            ], 404);
        }

        $availabilitySlot->delete();

        return response()->json([
            'message' => 'Franja de disponibilidad eliminada correctamente'
        ]);
    }

    /**
     * Listar franjas de disponibilidad de un entrenador específico (endpoint público)
     */
    public function getByCoach(int $coachId): JsonResponse
    {
        $availabilitySlots = AvailabilitySlot::where('coach_id', $coachId)
            ->with('sport')
            ->get();

        return response()->json($availabilitySlots);
    }
}
