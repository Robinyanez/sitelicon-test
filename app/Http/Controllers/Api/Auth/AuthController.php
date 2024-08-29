<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiController;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('authToken')->plainTextToken;

            $responseToken = [
                'user_id' => $user->id,
                'token_type'   => 'Bearer Token',
                'access_token' => $token,
            ];

            return $this->successResponse($responseToken, self::OK);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
