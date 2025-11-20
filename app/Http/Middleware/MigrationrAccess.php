<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MigrationrAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->migrationr === 'SAFE-ADMIN-KEY-1') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
