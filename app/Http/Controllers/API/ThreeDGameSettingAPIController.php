<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\ThreeDGameSetting\ThreeDGameSettingRepositoryInterface;

class ThreeDGameSettingAPIController extends Controller
{
    //
    private $settingRepo;

    public function __construct(ThreeDGameSettingRepositoryInterface $repo)
    {
        $this->settingRepo = $repo;
    }

    public function list(Request $request)
    {
        $this->settingRepo->listSettings($request);
    }

    public function create(Request $request)
    {
        if(!$request->opening_date_time || !$request->closing_date_time){
            ResponseMessage('Opening and closing datetimes must be provided', 400);
        }

        if(!$request->bet_multiplier  || !$request->twist_multiplier || !$request->closing_amount){
            ResponseMessage('Bet, twist multipliers and closing amount must be provided', 400);
        }

        $data = $request->all();
        $setting = $this->settingRepo->createGameSetting($data);

        ResponseData($setting);
    }
}
