<?php

namespace App\Repositories\Game;

use App\Models\Game;
use App\Models\GameSetting;
use Illuminate\Support\Facades\DB;

class GameRepository implements GameInterface
{
    public function list($request)
    {

    }

    public function updateOrCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $game = Game::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $game;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateOrCreateGameSetting($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $gameSetting = GameSetting::create(
                $data
            );
            DB::commit();
            return $gameSetting;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function toggleIsActive($request){
        $game = Game::where('id', $request->id)->first();

        if ($game) {
            $game->is_active = (int)$request->is_active;
            $game->save();
            return $game;
        }
        ResponseMessage('Data not found',404);
    }

    public function gameSetting($gameId){
        $gameSetting=GameSetting::where('game_id',$gameId)
        ->where('is_active',1)
        ->select(['id','name','lottery_date_time','is_active'])
        ->orderBy('id','desc')
        ->get();
        return $gameSetting;
    }

}
