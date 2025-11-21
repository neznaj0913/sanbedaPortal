<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckVisitorAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accessGranted = session('access_granted', false);
        $accessTime = session('access_granted_at');

        if ($accessGranted && $accessTime && Carbon::parse($accessTime)->addMonth()->isFuture()) {
            return $next($request); 
        }

        session()->forget(['access_granted', 'access_granted_at']);

        if ($request->isMethod('post')) {
            return redirect()->route('visitor.code')
                             ->with('error', 'Access code required before submitting the form.');
        }

        return redirect()->route('visitor.code');
    }
}
