<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Actions\Auth\APILoginAction;
use App\Models\PersonFcmToken;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        if (isset($request->credential_type)) {
            $credentialType = $request->credential_type == 'admin' ? 'App\Models\User' : 'App\Models\Agent';
            $credentialColumn = $request->credential_type == 'admin' ? 'username' : 'phone_number';
            $token_name = $request->credential_type == 'admin' ? 'admin_token' : 'agent_token';
            $credential = $request->credential_type == 'admin' ? 'user' : 'agent';
        } else {
            $credentialType = 'App\Models\User';
            $credentialColumn = 'username';
            $token_name = 'user_token';
            $credential = 'user';
        }
        $loginResponse = (new APILoginAction($credentialColumn, $request->$credentialColumn, $request->password, $credentialType))->run($token_name);
        $loginResponse['user']['login_type'] = $request->credential_type;
        if ($loginResponse["code"] != 200) {
            // ResponseData($loginResponse, 401, false);
            ResponseMessage($loginResponse["message"], 401);
        } else {
            $this->storeFcmToken($request->fcm_token, $loginResponse['user']['id'], $credential);
            ResponseData($loginResponse);
        }
    }

    public function logout(Request $request)
    {
        // dd($request->user());
        // dd($request->session());
        // $request->user()->currentAccessToken()->delete();
        // return redirect()->route('agent_login');
        // if (Auth::user()) {
        //     if (Auth::guard('api')->check()) {
                // $request->session()->invalidate();
        //         Auth::guard('api')->logout();
        //         $request->user()->currentAccessToken()->delete();
        //         return redirect()->route('login');
        //     }

        //     if (Auth::guard('agent_api')->check()) {
        //         // $request->session()->invalidate();
        //         Auth::guard('agent_api')->logout();
        //         $request->user()->currentAccessToken()->delete();
                // return redirect()->route('agent_login');
        //     }
        // }
        // ResponseMessage('Successfully logged out');

    }

    public function storeFcmToken($token, $userId, $credential)
    {
        if ($token) {
            $personToken = PersonFcmToken::firstOrCreate(
                [
                    'fcm_token' => $token,
                    'personable_id' => $userId,
                    'personable_type' => $credential,
                ],
                [
                    'fcm_token' => $token,
                    'personable_id' => $userId,
                    'personable_type' => $credential,
                ]
            );
            return $personToken;
        }
    }
}
