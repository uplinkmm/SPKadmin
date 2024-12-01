<?php

namespace App\Repositories\ThreeDGameSetting;

use DateInterval;

use Illuminate\Http\Request;

use App\Models\GameSetting;

class ThreeDGameSettingRepository implements ThreeDGameSettingRepositoryInterface
{
    public function listSettings(Request $request)
    {
        $pageNumber = $request->page ?? 1;
        $perPage = $request->per_page ?? 20;
        $totalCount = GameSetting::where('game_id', 2)->where('is_active', 1)->count();
        $skip = ($pageNumber - 1) * $perPage;
        $settings = GameSetting::where('game_id', 2)->where('is_active', 1)
        ->select(['id', 'opening_date_time', 'closing_date_time', 'lottery_date_time', 'bet_multiplier','twist_multiplier', 'closing_amount'])
        ->orderBy('id', 'desc')->skip($skip)->take($perPage)->get();
        $settings = MakePaginationData($request, $totalCount, $settings);

        ResponseData($settings);
    }

    public function createGameSetting(array $data)
    {
        // $data['game_id'] = 2;
        $data['game_id'] =$data['game_id'];
        // $data['time_status'] = 'evening';
        $closingDateTime = date_create($data['closing_date_time']);
        $lotteryDateTime = date_create($data['lottery_date_time']);
        // $lotteryDateTime = $closingDateTime->add(new DateInterval('PT30M'))->format('Y-m-d H:i:s');
        $data['lottery_date_time'] = $lotteryDateTime;
        return GameSetting::create($data);
    }
}
