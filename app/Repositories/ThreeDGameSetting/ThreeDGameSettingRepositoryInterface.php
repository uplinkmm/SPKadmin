<?php

namespace App\Repositories\ThreeDGameSetting;

use Illuminate\Http\Request;

interface ThreeDGameSettingRepositoryInterface
{
    public function listSettings(Request $request);

    public function createGameSetting(array $data);
}
