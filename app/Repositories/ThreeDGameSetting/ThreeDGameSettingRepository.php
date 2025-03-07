<?php

namespace App\Repositories\ThreeDGameSetting;

use DateInterval;

use App\Models\Game;

use App\Models\GameSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThreeDGameSettingRepository implements ThreeDGameSettingRepositoryInterface
{
    public function listSettings(Request $request)
    {
        $pageNumber = $request->page ?? 1;
        $perPage = $request->per_page ?? 20;
        $totalCount = GameSetting::where('game_id', 2)->where('is_active', 1)->count();
        $skip = ($pageNumber - 1) * $perPage;
        $settings = GameSetting::where('game_id', 2)->where('is_active', 1)
            ->select(['id', 'opening_date_time', 'closing_date_time', 'lottery_date_time', 'bet_multiplier', 'twist_multiplier', 'closing_amount'])
            ->orderBy('id', 'desc')->skip($skip)->take($perPage)->get();
        $settings = MakePaginationData($request, $totalCount, $settings);

        ResponseData($settings);
    }

    public function createGameSetting(array $data)
    {
        // $data['game_id'] = 2;
        DB::beginTransaction();
        try {
            $game = Game::where('type', '3d')->where('is_active', 1)->first();
            if (!$game) {
                ResponseMessage('Game is invalid', 419);
            }
            $data['game_id'] = $game->id;
            $closingDateTime = date_create($data['closing_date_time']);
            $lotteryDateTime = date_create($data['lottery_date_time']);
            // $lotteryDateTime = $closingDateTime->add(new DateInterval('PT30M'))->format('Y-m-d H:i:s');
            $data['lottery_date_time'] = $lotteryDateTime;
            $previousGameSetting = GameSetting::join('games', 'game_settings.game_id', 'games.id')
                ->where('games.is_active', 1)
                ->where('game_settings.is_active', 1)
                ->where('games.type', '3d')
                // ->get();
                ->update(
                    [
                        'game_settings.is_active' => 0
                    ]
                );
            $gameSetting = GameSetting::create($data);
            DB::commit();
            return $gameSetting;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
