<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd([Auth::guard('web')->user(),Auth::guard('agent')->user()]);
        // dd([
        //     'web_authenticated' => Auth::guard('web')->check(),
        //     'agent_authenticated' => Auth::guard('agent')->check(),
        //     'session' => session()->all(),
        // ]);
        // return $next($request);
        Auth::guard('agent')->logout();
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
