<?php

namespace App\Traits;

use App\Models\BettingWin;
use App\Models\GameSetting;

trait CheckBettingWin
{
    public function checkExistBettingWin($data)
    {
        $gameSetting = GameSetting::find($data['game_setting_id']);
        $date=now()->format('Y-m-d');
        $startTime = $date . ' ' . $gameSetting->opening_time;
        $endTime = $date . ' ' . $gameSetting->closing_time;
        $gameSettingId = $gameSetting->id;

        // if($data['game_setting_id'] == 1){
        //     $startTime = $date . ' 00:00:00';
        //     $endTime = $date . ' 12:01:00';
        // }
        // if($data['game_setting_id'] == 2){
        //     $startTime = $date . ' 12:30:00';
        //     $endTime = $date . ' 16:30:00';
        // }
        // dd([$data, $startTime, $endTime]);
        $bettingWin=BettingWin::where('game_setting_id',$gameSettingId)
        ->whereBetween('date_time',[$startTime, $endTime])
        ->first();

        // ->when($gameId==config('2d_setting.game_id'),function($q)use($date, $startTime, $endTime){
        //     $q->whereBetween('date_time',[$startTime, $endTime]);
        // })
        // ->first();
        // dd($bettingWin);
        if($bettingWin){
            ResponseMessage('Winning is already existed',419);
        }
    }

    public function checkExistThreeDBettingWin($data){
        // $date=now()->format('Y-m-d');
        $bettingWin=BettingWin::where('game_setting_id',$data['game_setting_id'])
        ->first();
        if($bettingWin){
            ResponseMessage('Winning is already existed',419);
        }
    }
}
