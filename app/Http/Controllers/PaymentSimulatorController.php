<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentSimulatorController extends Controller
{
    public function pay(Request $request)
    {
        $success = $request->input('success', true);

        if ($success) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pago simulado exitosamente.',
                'transaction_id' => uniqid('sim_')
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'El pago simulado ha fallado.'
            ], 400);
        }
    }
}
