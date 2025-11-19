<?php

use App\Models\AllowedEmail;

if (!function_exists('is_allowed_email')) {
    function is_allowed_email($email)
    {
        return AllowedEmail::where('email', $email)->exists();
    }
}
