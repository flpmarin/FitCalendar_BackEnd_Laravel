<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoachController extends Controller
{
    /**
     * Obtener perfil del entrenador actual
     */
    public function getProfile(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'No se encontró perfil de entrenador'
            ], 404);
        }

        // Cargar el perfil completo con relaciones
        $coach = Coach::where('id', $user->coach->id)
            ->with(['sports', 'organization'])
            ->first();

        return response()->json($coach);
    }

    /**
     * Actualizar perfil del entrenador
     */
    public function updateProfile(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'No se encontró perfil de entrenador'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'coach_type' => 'nullable|string|max:50',
            'organization_id' => 'nullable|exists:organizations,id',
            'payment_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->coach->update($request->all());

        return response()->json([
            'message' => 'Perfil de entrenador actualizado correctamente',
            'coach' => $user->coach
        ]);
    }

    /**
     * Asignar deportes al entrenador
     */
    public function assignSports(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->coach) {
            return response()->json([
                'message' => 'No se encontró perfil de entrenador'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'sports' => 'required|array',
            'sports.*.id' => 'required|exists:sports,id',
            'sports.*.specific_price' => 'nullable|numeric',
            'sports.*.specific_location' => 'nullable|string',
            'sports.*.session_duration_minutes' => 'nullable|integer|min:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $sportsData = [];
        foreach ($request->sports as $sport) {
            $sportsData[$sport['id']] = [
                'specific_price' => $sport['specific_price'] ?? null,
                'specific_location' => $sport['specific_location'] ?? null,
                'session_duration_minutes' => $sport['session_duration_minutes'] ?? 60,
            ];
        }

        $user->coach->sports()->sync($sportsData);

        return response()->json([
            'message' => 'Deportes asignados correctamente',
            'coach' => $user->coach->load('sports')
        ]);
    }
}
