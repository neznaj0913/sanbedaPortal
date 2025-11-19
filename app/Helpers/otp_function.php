<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

if (!function_exists('generate_and_send_otp')) {
    function generate_and_send_otp($user)
    {
        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp, 'is_verified' => false]);
        Mail::to($user->email)->send(new OtpMail($otp));
        return $otp;
    }
}
