<?php

namespace App\Traits;

use App\Models\BettingWin;
use App\Models\GameSetting;
use App\Models\ClosingNumber;
use Illuminate\Support\Facades\DB;

trait CustomerBetting
{

    public function getCustomerTotalBetAmountByGameSetting($gameId,$gameSettingId,$number,$closingAmount){
        $now = now();
        $date = $now->format('Y-m-d');
        $gameSetting = GameSetting::find($gameSettingId);
        if (!$gameSetting) {
            ResponseMessage('Game Setting Not Found', status_code: 404);
        }
        // $closingNumber = ClosingNumber::orderBy('id', 'desc')->where('number', $number)
        //     ->where('game_id', $gameId)
        //     ->where('game_setting_id', $gameSettingId)
        //     ->whereDate('date_time', $date)
        //     ->where('is_active')
        //     ->first();
        
        // // $closingAmount = config('2d_setting.max_closing_bet_amount');
        // $closingAmount = $closingNumber ? $closingNumber->amount : $gameSetting->closing_amount;


        $startTime = convertDateTimeFormat($date . $gameSetting->opening_time);
        $endTime = convertDateTimeFormat($date . $gameSetting->closing_time);
        $query = DB::table('betting_numbers as bn')
            ->join('bettings as b', 'bn.betting_id', '=', 'b.id')
            ->where('bn.number', $number);

        if ($gameId == 1) {
            // $max = config('2d_setting.max_closing_bet_amount');
            $query
                ->whereBetween('b.date_time', [$startTime, $endTime])
                ->where('b.game_id', $gameId)
                ->where('b.game_setting_id', $gameSettingId);
        } else if ($gameId == 2) {
            $query->where('b.game_setting_id', $gameSettingId);
        }

        // Retrieve the total bet amount and closing amount
        $query->select(
            DB::raw('COALESCE(SUM(bn.amount), 0) AS total_bet_amount'),
        );

        // Execute the query and fetch results
        $result = $query->first();
        return $result->total_bet_amount;
        // return [
        //     'total_bet_amount' => (int) $result->total_bet_amount,
        //     'closing_amount' => $closingAmount,
        // ];
    }
}