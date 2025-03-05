<?php

namespace App\Repositories\TwoDClosingNumber;

use Exception;
use App\Models\Betting;
use App\Models\GameSetting;
use App\Models\ClosingNumber;
use Illuminate\Support\Facades\DB;

class TwoDClosingNumberRepository implements TwoDClosingNumberRepositoryInterface
{
    public function createClosingNumber($request)
    {
        try {
            DB::beginTransaction();
            $numbers = isset($request->number) ? JsonDecode($request->number) : null;
            if (!$request->number || !$request->game_setting_id) {
                ResponseMessage('Number, amount and time status must be present', 400);
            }
            // $gameSetting = GameSetting::find($request->game_setting_id);
            $data = $request->except('number');
            $data['game_id'] = $request->game_id;
            $data['created_by'] = ApiUser()->id;
            $data['game_setting_id'] = $request->game_setting_id;
            $data['date_time'] = now();
            $closingNumbersData = [];
            foreach ($numbers as $number) {
                $data['number'] = $number;
                $existClosingNumber = ClosingNumber::where('game_id', $request->game_id)
                    ->where('game_setting_id', $request->game_setting_id)
                    ->where('number', $number)
                    ->whereDate('date_time', now())
                    ->where('is_active', 1)
                    ->first();
                if ($existClosingNumber) {
                    $existClosingNumber->update([
                        'is_active'=>0,
                    ]);
                }
                $closingNumbersData[] = $data;
            }
            ClosingNumber::insert($closingNumbersData);
            DB::commit();
            ResponseMessage('Closing numbers set successfully');
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function setInactiveClosingNumber($request)
    {
        $date = CurrentDate();
        $gameSetting = GameSetting::find($request->game_setting_id);
        $startTime = $date . ' ' . $gameSetting->opening_time;
        $endTime = $date . ' ' . $gameSetting->closing_time;
        // if($gameSetting->game->type=='2d'){
        // $startTime = $date . ' ' . $gameSetting->opening_time;
        // $endTime = $date . ' ' . $gameSetting->closing_time;
        // }
        // if($gameSetting->game->type=='3d'){
        //     $startTime = $gameSetting-
        //     $endTime = $date . ' ' . $gameSetting->closing_time;
        // }
        $gameSettingId = $gameSetting->id;
        try {
            DB::beginTransaction();
            $numbers = explode(',', $request->number);
            foreach ($numbers as $number) {
                $closingNumbers = ClosingNumber::orderBy('id', 'desc')
                    ->when($gameSetting->game->type == '2d', function ($q) use ($startTime, $endTime) {
                        $q->whereBetween('date_time', [$startTime, $endTime]);
                    })
                    ->where('game_setting_id', $gameSettingId)
                    ->where('number', $number)->get();
                foreach ($closingNumbers as $closingNumber) {
                    $closingNumber->is_active = 0;
                    $closingNumber->save();
                }
            }
            DB::commit();
            ResponseMessage('Closing numbers set to inactive successfully');
        } catch (Exception $e) {
            DB::rollBack();

            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function listClosingNumbers($gameSettingId)
    {
        $gameSetting = GameSetting::find($gameSettingId);
        $betting_number_list = $gameSetting->game->type == '2d' ? $this->get2dBettingNumberList($gameSetting) : $this->get3dBettingNumberList($gameSetting);
        return $betting_number_list;
    }

    public function get2dBettingNumberList($gameSetting)
    {
        $date = now()->format('Y-m-d');
        $gameSettingId = $gameSetting->id;
        $max = $gameSetting->closing_amount;
        $subqueryD1 = DB::table(DB::raw('(SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d1'));
        $subqueryD2 = DB::table(DB::raw('(SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d2'));

        $betsWithTotalAmount = DB::table(DB::raw('(' . $subqueryD1->toSql() . ') as d1'))
            ->mergeBindings($subqueryD1)
            ->crossJoin(DB::raw('(' . $subqueryD2->toSql() . ') as d2'))
            ->mergeBindings($subqueryD2)
            ->leftJoin('closing_numbers as cn', function ($join) use ($date, $gameSetting) {
                $join->on(DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0")'), '=', 'cn.number')
                    ->where('cn.game_setting_id', $gameSetting->id)
                    ->where('cn.is_active', 1)
                    ->whereDate('cn.date_time', $date);
            })
            ->select(
                DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0") AS number'),
                // DB::raw('CAST(COALESCE(MAX(cn.amount), ' . $max . ') AS UNSIGNED) AS closing_amount'),
                DB::raw('CAST(COALESCE(MAX(cn.amount)) AS UNSIGNED) AS closing_amount'),
                DB::raw('MAX(CASE WHEN cn.id IS NULL THEN 0 ELSE 1 END) AS is_closing'),
                DB::raw('MAX(CASE WHEN cn.id IS NULL THEN NULL ELSE cn.id END) AS closing_id')
            )
            ->groupBy(DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0")'))
            ->orderBy('number')
            ->get();
        $totalPercentage = $this->get2DBreakPercentage($gameSettingId, $date, $subqueryD1, $subqueryD2);
        return [
            'closing_number_list' => $betsWithTotalAmount,
            'break_percentage' => $totalPercentage,
        ];
    }

    public function get3dBettingNumberList($gameSetting)
    {
        $gameSettingId = $gameSetting->id;
        $now = now();

        $subqueryD1 = DB::table(DB::raw('(SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d1'));
        $subqueryD2 = DB::table(DB::raw('(SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d2'));
        $subqueryD3 = DB::table(DB::raw('(SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d3'));

        $betsWithTotalAmount = DB::table(DB::raw('(' . $subqueryD1->toSql() . ') as d1'))
            ->mergeBindings($subqueryD1)
            ->crossJoin(DB::raw('(' . $subqueryD2->toSql() . ') as d2'))
            ->mergeBindings($subqueryD2)
            ->crossJoin(DB::raw('(' . $subqueryD3->toSql() . ') as d3'))
            ->mergeBindings($subqueryD3)
            ->leftJoin('closing_numbers as cn', function ($join) use ($gameSetting) {
                $join->on(DB::raw('LPAD(d1.n * 100 + d2.n * 10 + d3.n, 3, "0")'), '=', 'cn.number')
                    ->where('cn.game_setting_id', $gameSetting->id)
                    ->where('cn.is_active', 1);
            })
            ->leftJoin('game_settings as gs', function ($join) use ($now) {
                $join->on('gs.game_id', '=', DB::raw(config('3d_setting.game_id')))
                    ->where('gs.is_active', 1)
                    ->where(function ($query) use ($now) {
                        $query->where('gs.opening_date_time', '<=', $now)
                            ->where('gs.closing_date_time', '>=', $now);
                    });
            })
            ->select(
                DB::raw('LPAD(d1.n * 100 + d2.n * 10 + d3.n, 3, "0") AS number'),
                DB::raw('CAST(COALESCE(MAX(cn.amount)) AS UNSIGNED) AS closing_amount'),
                DB::raw('MAX(CASE WHEN cn.id IS NULL THEN 0 ELSE 1 END) AS is_closing'),
            )
            ->groupBy(
                DB::raw('LPAD(d1.n * 100 + d2.n * 10 + d3.n, 3, "0")')
            )
            ->orderBy('number')
            ->get();
        $totalPercentage = $this->get3DBreakPercentage($gameSettingId, $now, $subqueryD1, $subqueryD2, $subqueryD3);
        return [
            'closing_number_list' => $betsWithTotalAmount,
            'break_percentage' => $totalPercentage,
        ];
    }

    public function get3DBreakPercentage($gameSettingId, $now, $subqueryD1, $subqueryD2, $subqueryD3)
    {
        $totalBetAmount = Betting::where('game_setting_id', $gameSettingId)
            ->sum('total_amount');
        $totalClosingAmount = DB::table(DB::raw('(' . $subqueryD1->toSql() . ') as d1'))
            ->mergeBindings($subqueryD1)
            ->crossJoin(DB::raw('(' . $subqueryD2->toSql() . ') as d2'))
            ->mergeBindings($subqueryD2)
            ->crossJoin(DB::raw('(' . $subqueryD3->toSql() . ') as d3'))
            ->mergeBindings($subqueryD3)
            ->leftJoin('closing_numbers as cn', function ($join) use ($gameSettingId) {
                $join->on(DB::raw('LPAD(d1.n * 100 + d2.n * 10 + d3.n, 3, "0")'), '=', 'cn.number')
                    ->where('cn.game_setting_id', $gameSettingId)
                    ->where('cn.is_active', 1);
            })
            ->leftJoin('game_settings as gs', function ($join) use ($now) {
                $join->on('gs.game_id', '=', DB::raw(config('3d_setting.game_id')))
                    ->where('gs.is_active', 1)
                    ->where(function ($query) use ($now) {
                        $query->where('gs.opening_date_time', '<=', $now)
                            ->where('gs.closing_date_time', '>=', $now);
                    });
            })
            ->select(DB::raw('SUM(COALESCE(cn.amount, gs.closing_amount)) AS total_closing_amount'))
            ->first();
        // $breakPercentage=$totalBetAmount/$totalAmount*100;
        $breakPercentage = number_format(($totalBetAmount / $totalClosingAmount->total_closing_amount) * 100, 2);
        return $breakPercentage;
    }

    public function get2DBreakPercentage($gameSettingId, $date, $subqueryD1, $subqueryD2)
    {
        $totalBetAmount = Betting::where('game_setting_id', $gameSettingId)
            ->whereDate('date_time', $date)
            ->sum('total_amount');
        // dd($totalBetAmount);
        $totalClosingAmount = DB::table(DB::raw('(' . $subqueryD1->toSql() . ') as d1'))
            ->mergeBindings($subqueryD1)
            ->crossJoin(DB::raw('(' . $subqueryD2->toSql() . ') as d2'))
            ->mergeBindings($subqueryD2)
            ->crossJoin(DB::raw('(SELECT closing_amount FROM game_settings WHERE id = ' . intval($gameSettingId) . ') as gs'))
            // ->crossJoin(DB::raw('(SELECT closing_amount FROM game_settings WHERE id = ' . intval($gameSettingId) . ' AND is_active = 1) as gs'))
            ->leftJoin('closing_numbers as cn', function ($join) use ($date, $gameSettingId) {
                $join->on(DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0")'), '=', 'cn.number')
                    ->where('cn.game_setting_id', $gameSettingId)
                    ->where('cn.is_active', 1)
                    ->whereDate('cn.date_time', $date);
            })
            // ->select(
            //     DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0") AS number'),
            //     DB::raw('MAX(CASE WHEN cn.id IS NULL THEN 0 ELSE 1 END) AS is_closing'),
            //     DB::raw('MAX(CASE WHEN cn.id IS NULL THEN NULL ELSE cn.id END) AS closing_id'),
            //     DB::raw('SUM(COALESCE(cn.amount, gs.closing_amount)) AS total_closing_amount')
            // )
            // ->groupBy(DB::raw('LPAD(d1.n * 10 + d2.n, 2, "0")'))
            // ->orderBy('number')
            ->select(DB::raw('SUM(COALESCE(cn.amount, gs.closing_amount)) AS total_closing_amount'))
            ->first();
        $breakPercentage = number_format(($totalBetAmount / (int) $totalClosingAmount->total_closing_amount) * 100, 2);
        // dd($breakPercentage);
        return $breakPercentage;
    }
}
