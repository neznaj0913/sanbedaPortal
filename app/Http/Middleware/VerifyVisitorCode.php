<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyVisitorCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  public function handle($request, Closure $next)
{
    if (!session('visitor_code_verified')) {
        return redirect()->route('visitor.code');
    }

    return $next($request);
}

}
