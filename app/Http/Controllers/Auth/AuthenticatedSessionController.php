<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Para solicitudes de API
        if ($request->wantsJson() || $request->bearerToken()) {
            // Autenticación basada en tokens (API)
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            return response()->json(['message' => 'Logged out successfully']);
        } else {
            // Autenticación basada en sesiones (Filament/Web)
            Auth::guard('web')->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->noContent();
        }
    }
}
