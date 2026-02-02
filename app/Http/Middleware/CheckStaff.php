<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isStaff())) {
            return $next($request);
        }
        
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('error', 'You do not have staff access.');
        }
        
        return redirect()->route('login');
    }
}
