<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    /**
     * Obtener el perfil del usuario autenticado
     */
    public function getProfile(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();


        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'language' => $user->language,
            'age' => $user->age,
            'description' => $user->description,
            'profile_picture_url' => $user->profile_picture_url,
        ]);
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();


            Log::info('Actualizando perfil para usuario: ' . $user->id, $request->all());

            $validator = Validator::make($request->all(), [
                'age' => 'nullable|integer|min:10|max:120',
                'description' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación fallida', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];
            if ($request->has('age')) {
                $updateData['age'] = $request->age;
            }
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }

            Log::info('Datos a actualizar', $updateData);
            $user->update($updateData);

            return response()->json([
                'message' => 'Perfil actualizado exitosamente',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'age' => $user->age,
                    'description' => $user->description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el perfil: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al actualizar el perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
