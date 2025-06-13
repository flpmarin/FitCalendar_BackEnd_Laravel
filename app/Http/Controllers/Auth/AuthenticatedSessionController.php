<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
//        $request->validate([
//            'email' => ['required', 'email'],
//            'password' => ['required'],
//        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        // Elimina todos los tokens anteriores del usuario
        $user->tokens()->delete();

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
            Log::info('Token eliminado para: ' . $user->email);

            return response()->json(['message' => 'Logged out']);
        }

        Log::warning('Logout llamado sin token válido o usuario no autenticado');
        return response()->json(['message' => 'Invalid token or unauthenticated.'], 401);
    }


}
