<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $guard = Auth::getDefaultDriver();
        // if(Auth::guard('web')->check()){
        //     Auth::guard('agent')->logout();
        // }
        // if(Auth::guard('agent')->check()){
        //     Auth::guard('web')->logout();
        // }
        // $request->session()->invalidate();
        if (!Auth::guard('web')->check() && !Auth::guard('agent')->check()) {
            return redirect()->route('login'); // Redirect to login if neither is authenticated
        }
        return $next($request);
    }
}
