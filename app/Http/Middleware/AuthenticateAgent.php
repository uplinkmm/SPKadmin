<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    /**
     * Handle an incoming request.  
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::getDefaultDriver();
        // Auth::guard('web')->logout();
        // if (!Auth::guard('agent')->check()) {
        //     return redirect()->route('agent_login');
        // }
        // if (!Auth::guard(name: 'agent')->check()) {
        // }
        if (!Auth::guard('web')->check() && !Auth::guard('agent')->check()) {
            return redirect()->route('login'); // Redirect to login if neither is authenticated
        }
        return $next($request);
    }
}
