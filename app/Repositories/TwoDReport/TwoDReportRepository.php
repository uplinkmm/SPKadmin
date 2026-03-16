<?php

namespace App\Repositories\TwoDReport;

use Illuminate\Support\Facades\DB;

use App\Models\BettingNumber;
use App\Models\Customer;
use App\Models\GameSetting;
use App\Models\Betting;


class TwoDReportRepository implements TwoDReportRepositoryInterface
{
    public function getBettingNumbersWithTotalAmount($request)
    {
        // $request['date']='2024-09-28';
        $gameSetting = GameSetting::find($request->game_setting_id);
        $date=$request->date;
        $startTime = $date . ' ' . $gameSetting->opening_time;
        $endTime = $date . ' ' . $gameSetting->closing_time;
        $whereDate = convertDateFormat($date);
        $gameSettingId = $gameSetting->id;
        // if($timeStatus == 'morning'){
        //     $startTime = $date . ' 00:00:00';
        //     $endTime = $date . ' 12:01:00';
        // }
        // else{
        //     $startTime = $date . ' 12:30:00';
        //     $endTime = $date . ' 23:59:00';
        // }
        $totalAmount = Betting::where('game_setting_id', $gameSettingId)
            ->whereDate('date_time', $whereDate)
            ->sum('total_amount');
        $betsWithTotalAmount = DB::select('
            WITH numbers AS (
            SELECT LPAD(d1.n * 10 + d2.n, 2, "0") AS number
            FROM (
                SELECT 0 AS n
                UNION ALL
                SELECT 1
                UNION ALL
                SELECT 2
                UNION ALL
                SELECT 3
                UNION ALL
                SELECT 4
                UNION ALL
                SELECT 5
                UNION ALL
                SELECT 6
                UNION ALL
                SELECT 7
                UNION ALL
                SELECT 8
                UNION ALL
                SELECT 9
            ) AS d1
            CROSS JOIN (
                SELECT 0 AS n
                UNION ALL
                SELECT 1
                UNION ALL
                SELECT 2
                UNION ALL
                SELECT 3
                UNION ALL
                SELECT 4
                UNION ALL
                SELECT 5
                UNION ALL
                SELECT 6
                UNION ALL
                SELECT 7
                UNION ALL
                SELECT 8
                UNION ALL
                SELECT 9
            ) AS d2
        ),
        filtered_bets AS (
            SELECT bn.number, bn.amount
            FROM betting_numbers bn
            JOIN bettings b ON bn.betting_id = b.id
            WHERE b.game_setting_id = ?
            AND b.date_time >= ? and b.date_time <= ?
            AND b.game_id = 1
        )
        SELECT
            n.number,
            COALESCE(SUM(fb.amount), 0) AS total_bet_amount
        FROM
            numbers n
        LEFT JOIN
            filtered_bets fb ON n.number = fb.number
        GROUP BY
            n.number
        ORDER BY
            n.number;
        ', [$gameSettingId, $startTime, $endTime]);

        // return $betsWithTotalAmount;
        return ['dashboard' => $betsWithTotalAmount, 'total_amount' => $totalAmount];

    }

    public function getBettingNumberCounts(GameSetting $gameSetting, $request)
    {
        // $startTime = $date . ' ' . $gameSetting->opening_time;
        // $endTime = $date . ' ' . $gameSetting->closing_time;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $gameSettingId = $gameSetting->id;

        // if($timeStatus == 'morning'){
        //     $startTime = $date . ' 00:00:00';
        //     $endTime = $date . ' 12:01:00';
        // }
        // else{
        //     $startTime = $date . ' 12:30:00';
        //     $endTime = $date . ' 23:59:00';
        // }
        // selectRaw('number,is_win,
        //  COUNT(number) as bets,
        //   CAST((SUM(betting_multiplier)/COUNT(betting_multiplier)) AS DOUBLE) as betting_multiplier,
        //    SUM(amount) as total_amount'

        //    )
        $bettedNumbersWithCount = [];
        $grand_total_amount = 0;
        $total_amount = 0;
        $total_prize = 0;
        $grandTotalAmount = BettingNumber::orderby('id', 'desc')
            ->join('bettings', 'betting_numbers.betting_id', 'bettings.id')
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('bettings.date_time', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('bettings.date_time', '=', now()->format('Y-m-d'));
            })
            ->sum('amount');
        $groupedBettingNumbers = BettingNumber::
            selectRaw('
        number,
        is_win,
        COUNT(number) as bets,
        CAST((SUM(betting_multiplier) / COUNT(betting_multiplier)) AS DOUBLE) as betting_multiplier,
        SUM(amount) as total_amount,
        (CAST((SUM(betting_multiplier) / COUNT(betting_multiplier)) AS DOUBLE) * SUM(amount)) as total_bingo_amount,
        (' . $grandTotalAmount . ' - (CAST((SUM(betting_multiplier) / COUNT(betting_multiplier)) AS DOUBLE) * SUM(amount))) as total_prize
    ')
            ->whereIn('betting_id', function ($query) use ($gameSettingId, $from_date, $to_date, $searchInput) {
                $query->select('id')
                    ->from('bettings')
                    ->where('game_id', 1)
                    ->where('game_setting_id', $gameSettingId)
                    ->when($searchInput, function ($q) use ($searchInput) {
                        $q->where(function ($query) use ($searchInput) {
                            $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
                        });
                    })
                    ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                        $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
                    })
                    ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                        $q->whereDate('bettings.date_time', '>=', $from_date);
                    })
                    ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                        $q->whereBetween('bettings.date_time', [now(), $to_date]);
                    })
                    ->when(($from_date == null && $to_date == null), function ($q) {
                        $q->whereDate('bettings.date_time', '=', now()->format('Y-m-d'));
                    });
                // ->whereBetween('date_time', [$startTime, $endTime]);
            })
            ->groupBy('number', 'is_win')
            ->orderBy('total_amount', 'desc')
            ->get();
        // return $groupedBettingNumbers;
        // foreach ($groupedBettingNumbers as $groupedBettingNumber) {
        //     $grand_total_amount += $groupedBettingNumber->total_amount;
        //     $total_amount = $groupedBettingNumber->total_amount;
        //     $total_prize = $total_amount * $groupedBettingNumber->betting_multiplier;
        //     array_push($bettedNumbersWithCount, [
        //         'number' => $groupedBettingNumber->number,
        //         'bets' => $groupedBettingNumber->bets,
        //         'is_win' => $groupedBettingNumber->is_win,
        //         'amount' => $total_amount,
        //         'multiplier' => $groupedBettingNumber->betting_multiplier,
        //         'total_prize' => $total_prize,
        //     ]);
        // }
        // $report = ['bet_numbers' => $bettedNumbersWithCount, 'total_amount' => $grand_total_amount];
        $report = ['bet_numbers' => $groupedBettingNumbers, 'total_amount' => $grandTotalAmount];
        return $report;
    }

    public function getBettingAmountsByCustomer(GameSetting $gameSetting, $request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $perPage = $request->per_page ?? config('common.per_page');
        // $startTime = $date . ' ' . $gameSetting->opening_time;
        // $endTime = $date . ' ' . $gameSetting->closing_time;
        $gameSettingId = $gameSetting->id;

        $gameId = 1;
        $total_amount = DB::table('bettings')
            ->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date])
            ->where('game_id', $gameId)
            ->where('game_setting_id', $gameSettingId)
            ->sum('total_amount');

        //without pagination 
        // $customersWithBetAmounts = Betting::select(
        //     'customers.id',
        //     'customers.name',
        //     'customers.phone_number',
        //     DB::raw('COALESCE(SUM(betting_numbers.amount), 0) AS total_betted_amount'),
        //     // DB::raw('COALESCE(SUM(bettings.amount), 0) AS total_betted_amount'),
        //     DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 0) AS DOUBLE) as betting_multiplier'),
        //     DB::raw('COUNT(DISTINCT bettings.id) AS total_bettings'),
        //     DB::raw('ROUND((COALESCE(SUM(betting_numbers.amount), 0) / ' . $total_amount . ') * 100, 2) AS betting_percentage')
        // )
        //     ->leftjoin('betting_numbers', function ($join) {
        //         $join->on('betting_numbers.betting_id', '=', 'bettings.id');
        //     })
        //     ->join('customers', 'bettings.customer_id', 'customers.id')
        //     ->where('bettings.game_id', $gameId)
        //     ->where('bettings.game_setting_id', $gameSettingId)
        //     // ->whereBetween(DB::raw('DATE(bettings.date_time)'), [$startDate, $endDate]);
        //     ->when($searchInput, function ($q) use ($searchInput) {
        //         $q->where(function ($query) use ($searchInput) {
        //             $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
        //                 ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
        //         });
        //     })
        //     ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
        //         $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
        //     })
        //     ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
        //         $q->whereDate('bettings.date_time', '>=', $from_date);
        //     })
        //     ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
        //         $q->whereBetween('bettings.date_time', [now(), $to_date]);
        //     })
        //     ->when(($from_date == null && $to_date == null), function ($q) {
        //         $q->whereDate('bettings.date_time', '=', now()->format('Y-m-d'));
        //     })
        //     ->groupBy('customers.id', 'customers.name', 'customers.phone_number')
        //     ->orderByDesc('total_betted_amount')
        //     ->get();
        // foreach ($customersWithBetAmounts as $customer) {
        //     $customer['total_bingo_amount'] =
        //         BettingNumber::where('is_win', 1)->whereIn('betting_id', function ($query) use ($from_date, $to_date, $gameId, $gameSettingId, $customer) {
        //             $query->select('id')->from('bettings')
        //                 ->where('customer_id', $customer->id)
        //                 // ->whereBetween('date_time', [$startTime, $endTime])
        //                 // ->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date])
        //                 ->where('bettings.game_setting_id', $gameSettingId)
        //                 // ->whereBetween(DB::raw('DATE(bettings.date_time)'), [$startDate, $endDate]);
        //                 ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
        //                     $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
        //                 })
        //                 ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
        //                     $q->whereDate('bettings.date_time', '>=', $from_date);
        //                 })
        //                 ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
        //                     $q->whereBetween('bettings.date_time', [now(), $to_date]);
        //                 })
        //                 ->when(($from_date == null && $to_date == null), function ($q) {
        //                     $q->whereDate('bettings.date_time', '>=', now()->format('Y-m-d'));
        //                 })
        //                 ->where('game_id', $gameId)
        //                 ->where('game_setting_id', $gameSettingId);
        //         })->sum('amount') * $customer->betting_multiplier;
        // }
        //without pagination
        $customersWithBetAmounts = Betting::select(
            'customers.id',
            'customers.name',
            'customers.phone_number',
            DB::raw('COALESCE(SUM(betting_numbers.amount), 0) AS total_betted_amount'),
            DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 0) AS DOUBLE) as betting_multiplier'),
            DB::raw('COUNT(DISTINCT betting_numbers.id) AS total_bettings'),
            DB::raw('ROUND((COALESCE(SUM(betting_numbers.amount), 0) / ' . $total_amount . ') * 100, 2) AS betting_percentage'),
            DB::raw('COALESCE(SUM(CASE WHEN betting_numbers.is_win = 1 THEN betting_numbers.amount * betting_numbers.betting_multiplier ELSE 0 END), 0) AS total_bingo_amount')
        )
            ->leftJoin('betting_numbers', 'betting_numbers.betting_id', '=', 'bettings.id')
            ->join('customers', 'bettings.customer_id', 'customers.id')
            ->where('bettings.game_id', $gameId)
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('bettings.date_time', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('bettings.date_time', '=', now()->format('Y-m-d'));
            })
            ->groupBy('customers.id', 'customers.name', 'customers.phone_number')
            ->orderByDesc('total_betted_amount')
            ->paginate($perPage);

        return ['customer_bet_amounts' => $customersWithBetAmounts, 'all_total_betted_amount' => $total_amount];
    }

    public function getBettingCustomers(GameSetting $gameSetting, $request)
    {
        // $date = $request->date;
        // $startTime = $date . ' ' . $gameSetting->opening_time;
        // $endTime = $date . ' ' . $gameSetting->closing_time;
        // $from_date = convertDateFormat('2024-08-19');
        // $to_date = convertDateFormat('2024-08-21');
        $perPage = $request->per_page ?? config('common.per_page');

        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $gameSettingId = $gameSetting->id;
        $gameId = 1;
        $bettingCustomers = Customer::select(
            'customers.id',
            'customers.name',
            'customers.phone_number',
            'betting_numbers.number',
            'bettings.date_time',
            DB::raw('SUM(betting_numbers.amount) AS total_betted_amount')
        )
            ->join('bettings', function ($join) use ($gameId, $gameSettingId) {
                $join->on('customers.id', '=', 'bettings.customer_id')
                    ->where('game_setting_id', '=', $gameSettingId)
                    ->where('game_id', '=', $gameId);
            })
            ->join('betting_numbers', function ($join) {
                $join->on('bettings.id', '=', 'betting_numbers.betting_id');
                //  ->where('betting_numbers.is_win', 0);
            })

            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('bettings.date_time', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('bettings.date_time', '>=', now()->format('Y-m-d'));
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            // ->when(($searchInput), function ($q) use ($searchInput) {
            //     $q->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
            //         ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
            // })
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'betting_numbers.number',
                'bettings.date_time',
            )
            ->orderByDesc('total_betted_amount')
            ->paginate($perPage);
        // ->paginate($perPage);


        $timeStatus = date_create($gameSetting->lottery_time)->format('H:i') . ' ' . date_create($gameSetting->lottery_time)->format('A');

        return ['betting_customers' => $bettingCustomers, 'date' => null, 'time' => null];
    }

    public function getBingoCustomers($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        // $startTime = $date . ' ' . $gameSetting->opening_time;
        // $endTime = $date . ' ' . $gameSetting->closing_time;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;


        $gameSettingId = $request->game_setting_id;
        $gameSetting = GameSetting::find($gameSettingId);
        if ($gameSetting) {
            $gameId = 1;
            $bingoCustomers = Customer::select(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'betting_numbers.number',
                'bettings.date_time',
                DB::raw('SUM(betting_numbers.amount) AS total_betted_amount'),
                DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 2) AS DOUBLE) AS betting_multiplier'),
                DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 2) AS DOUBLE) * SUM(betting_numbers.amount) AS bingo_amount')
            )
                ->join('bettings', function ($join) use ($gameId, $gameSettingId) {
                    $join->on('customers.id', '=', 'bettings.customer_id')
                        ->where('game_setting_id', '=', $gameSettingId)
                        ->where('game_id', '=', $gameId);
                })
                ->join('betting_numbers', function ($join) {
                    $join->on('bettings.id', '=', 'betting_numbers.betting_id')
                        ->where('betting_numbers.is_win', '=', 1);
                })
                ->when($searchInput, function ($q) use ($searchInput) {
                    $q->where(function ($query) use ($searchInput) {
                        $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
                            ->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                    });
                })
                ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                    $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
                })
                ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                    $q->whereDate('bettings.date_time', '>=', $from_date);
                })
                ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                    $q->whereBetween('bettings.date_time', [now(), $to_date]);
                })
                ->when(($from_date == null && $to_date == null), function ($q) {
                    $q->whereDate('bettings.date_time', '>=', now()->format('Y-m-d'));
                })
                ->groupBy(
                    'customers.id',
                    'customers.name',
                    'customers.phone_number',
                    'betting_numbers.number',
                    'bettings.date_time'
                )
                ->orderByDesc('total_betted_amount');
            $bingoCustomers = isset($request->per_page) ? $bingoCustomers->paginate($perPage) : $bingoCustomers->get();
            $timeStatus = date_create($gameSetting->lottery_time)->format('H:i') . ' ' . date_create($gameSetting->lottery_time)->format('A');
            return ['bingo_customers' => $bingoCustomers, 'date' => $from_date, 'time' => $timeStatus];
        }

    }
}
