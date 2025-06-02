<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoachSession;
use Illuminate\Support\Facades\Auth;

class CoachSessionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'sport' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
        ]);

        $session = CoachSession::create([
            'coach_id' => Auth::id(),
            'date' => $validated['date'],
            'time' => $validated['time'],
            'sport' => $validated['sport'],
            'price' => $validated['price'],
            'location' => $validated['location'],
        ]);

        return response()->json($session, 201);
    }
}