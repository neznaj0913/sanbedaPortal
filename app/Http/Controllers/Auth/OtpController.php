<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\AllowedEmail;

class OtpController extends Controller
{
    // ✅ Send OTP to San Beda emails (auto-add new ones)
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = strtolower(trim($request->email));

        // ✅ Step 1: Check domain
        if (!str_ends_with($email, '@sanbeda-alabang.edu.ph')) {
            return response()->json([
                'success' => false,
                'message' => 'Only @sanbeda-alabang.edu.ph emails are allowed.'
            ]);
        }

        // ✅ Step 2: Auto-add to allowed_emails if not exists
        $allowed = AllowedEmail::where('email', $email)->first();

        if (!$allowed) {
            AllowedEmail::create(['email' => $email]);
            Log::info("Added new allowed email: {$email}");
        }

        // ✅ Step 3: Generate and store OTP
        $otp = rand(100000, 999999);
        $key = 'registration_otp:' . $email;
        Cache::put($key, $otp, now()->addMinutes(10));

        Log::info("OTP for {$email}: {$otp}");

        try {
            // ✅ Send OTP email
            Mail::raw("Your San Beda registration OTP is: {$otp}", function ($message) use ($email) {
                $message->to($email)->subject('San Beda Portal OTP Verification');
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Mail error sending OTP to {$email}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ]);
        }
    }

    // ✅ Verify OTP
    public function checkOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $email = strtolower(trim($request->email));
        $otp = $request->otp;
        $key = 'registration_otp:' . $email;
        $stored = Cache::get($key);

        Log::info("Checking OTP: email={$email} provided={$otp} stored={$stored}");

        if (!$stored) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found or it expired. Please request a new OTP.'
            ]);
        }

        if ((string)$stored === (string)$otp) {
            Cache::put('registration_verified:' . $email, true, now()->addMinutes(15));
            Cache::forget($key);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP. Try again.'
        ]);
    }
}
