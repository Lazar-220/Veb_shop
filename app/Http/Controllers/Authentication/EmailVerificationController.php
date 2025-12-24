<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return response()->json([
                'message' => 'Verifikacioni link je istekao ili nije validan.'
            ], 401);
        }

        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email je već verifikovan.'
            ],200);
        }

        $user->email_verified_at=now(); //direktno pristupa bazi
        $user->save();

        // $user->update([   //update koristi fillable tj. atribute modela da pristupi bazi
        //     'email_verified_at' => now()   //mi nemamo ovaj atr...
        // ]);

        return response()->json([
            'message' => 'Email uspešno verifikovan. Možete se prijaviti.'
        ],200);
    }
}
