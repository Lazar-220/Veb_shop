<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validacija nije prosla.',
                'errors'=>$validator->errors()
            ],422);
        }
        $data=$validator->validated();

        if(!Auth::attempt($data)){
            return response()->json([
                'message' => 'Pogrešan email ili lozinka.'
            ], 401);
        }

        $user = Auth::user();

        if ($user->email_verified_at==null) {
            return response()->json([
                'message' => 'Morate verifikovati email pre prijave.'
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message'=>'Uspesno ste se prijavili',
            'token' => $token,
            'user' => $user
        ],200);
    }
}

