<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = strtolower(trim($request->email));

        if (Cache::get('registration_verified:' . $email)) {
            return response()->json([
                'success' => true,
                'message' => 'Email verified and ready for registration.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email not verified yet. Please verify your OTP.'
        ]);
    }
}
