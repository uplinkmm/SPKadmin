<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\GameSetting;

class CommonController extends Controller
{
    //
    public function twoDGameSettings()
    {
        $gameSettings = GameSetting::where('game_id', 1)->orderBy('id')->get();

        ResponseData($gameSettings);
    }

    public function threeDGameSettings()
    {
        $gameSettings = GameSetting::where('game_id', 2)->get();

        ResponseData($gameSettings);
    }
}
