<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $isAgent = $request->input('credential_type') === 'agent';
        $guard = $isAgent ? 'agent' : 'web';
        $credentialColumn = $isAgent ? 'phone_number' : 'username';
        $credentials = [$credentialColumn => $request->$credentialColumn, 'password' => $request->password];
        $remember = false;
        if ($request->remember) {
            $remember = true;
        }
        if (Auth::guard($guard)->attempt($credentials, $remember)) {
            // Redirect based on user type
            // return redirect()->route($isAgent ? 'agents_users' : 'topup_transactions.index');
            if ($isAgent) {
                Auth::guard('web')->logout();
                return redirect()->route('agents_users');
            } else {
                if (ApiUser()->isSuperAdmin()) {
                    return redirect()->route('topup_transactions.index');
                } elseif (ApiUser()->isAdmin()) {
                    $firstPermission = ApiUser()->permissions->first();
                    if ($firstPermission) {
                        $routeName = config('permission_route.' . $firstPermission->slug);
                        return redirect()->route($routeName);
                    }
                }
                Auth::guard('agent')->logout();
                return redirect()->route('login');
            }

        } else {
            return redirect()->route($isAgent ? 'agent_login' : 'login');
        }
    }

    public function logout(Request $request)
    {
        // dd($request->user('sanctum'));
        $request->session()->invalidate();
        if (Auth::user()) {
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->user()->tokens()->delete();
                $request->session()->invalidate();
                // $request->session()->regenerateToken();
                Auth::guard('web')->logout();
                return redirect()->route('login');
            }
        }
        if (Auth::guard('agent')->user()) {
            Auth::guard('agent')->user()->tokens()->delete();
            $request->session()->invalidate();
            Auth::guard('agent')->logout();
            return redirect()->route('agent_login');
        }
        return redirect()->route('login');

    }
}
