<?php

namespace App\Repositories\ThreeDReport;

use App\Models\Game;

use App\Models\Betting;
use App\Models\Customer;
use App\Models\GameSetting;
use App\Models\BettingNumber;
use Illuminate\Support\Facades\DB;

class ThreeDReportRepository implements ThreeDReportRepositoryInterface
{
    public function getBettingNumbersWithTotalAmount(int $gameSettingId, int $page)
    {
        $perPage = 100;
        if ($page == 0) {
            $perPage = 1000;  // There are 1000 possible numbers from 000 to 999
            $offset = 0;      // No offset when retrieving all
        } else {
            // Calculate offset for pagination
            $offset = ($page - 1) * $perPage;
        }
        $totalAmount = Betting::where('game_setting_id', $gameSettingId)->sum('total_amount');
        $betsWithTotalAmount = DB::select('
            WITH numbers AS (
            SELECT LPAD(d1.n * 100 + d2.n * 10 + d3.n, 3, "0") AS number
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
            ) AS d3
        ),
        filtered_bets AS (
            SELECT bn.number, bn.amount
            FROM betting_numbers bn
            JOIN bettings b ON bn.betting_id = b.id
            WHERE b.game_setting_id = ?
            AND b.game_id = 2
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
            n.number
        LIMIT ? OFFSET ?;
        ', [$gameSettingId, $perPage, $offset]);
        return ['dashboard' => $betsWithTotalAmount, 'total_amount' => $totalAmount];
        // return $betsWithTotalAmount;
    }

    public function getBettingNumberCountsOld($request)
    {
        // $perPage=$request->per_page ?? config('common.per_page');

        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $gameSettingId = $request->game_setting_id;
        $bettedNumbersWithCount = [];
        $grand_total_amount = 0;
        $total_amount = 0;
        $total_prize = 0;
        // $groupedBettingNumbers = BettingNumber::selectRaw('number, COUNT(number) as bets, CAST((SUM(betting_multiplier)/COUNT(betting_multiplier)) AS DOUBLE) as betting_multiplier, SUM(amount) as total_amount')
        //     ->whereIn('betting_id', function ($query) use ($request, $from_date, $to_date, $gameSettingId, $searchInput) {
        //         $query->select('id')
        //             ->from('bettings')
        //             ->where('game_id', 2)
        //             ->where('game_setting_id', $gameSettingId)

        //             ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
        //                 $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
        //             })
        //             ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
        //                 $q->whereDate('bettings.date_time', '>=', $from_date);
        //             })
        //             ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
        //                 $q->whereBetween('bettings.date_time', [now(), $to_date]);
        //             });
        //     })
        //     ->when($searchInput, function ($q) use ($searchInput) {
        //         $q->where(function ($query) use ($searchInput) {
        //             $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
        //         });
        //     })
        //     ->groupBy('number')
        //     ->orderBy('total_amount', 'desc')
        //     ->get();
        // selectRaw('betting_numbers.number, 
        // COUNT(betting_numbers.number) as bets, 
        // CAST((SUM(betting_numbers.betting_multiplier)/COUNT(betting_numbers.betting_multiplier)) AS DOUBLE) as betting_multiplier,
        //  SUM(betting_numbers.amount) as total_amount,
        //  CAST(game_settings.twist_multiplier* SUM(betting_numbers.amount) as total_amount),   
        //  (SUM(betting_numbers.amount) * game_settings.twist_multiplier) as multiplied_total')

        #old correct query
        //     $groupedBettingNumbers = BettingNumber::selectRaw('
        //     betting_numbers.number, 
        //     COUNT(betting_numbers.number) as bets, 
        //     CAST((SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier)) AS DOUBLE) as betting_multiplier, 
        //     SUM(betting_numbers.amount) as total_amount,
        //     (SUM(betting_numbers.amount) * game_settings.twist_multiplier) as twist_amount
        // ')
        //         ->join('bettings', 'betting_numbers.betting_id', '=', 'bettings.id') // Join with the bettings table
        //         ->join('game_settings', 'bettings.game_setting_id', '=', 'game_settings.id') // Join with the game_settings table
        //         ->where('bettings.game_id', 2)
        //         ->where('bettings.game_setting_id', $gameSettingId)
        //         ->when($request->from_date && $request->to_date, function ($q) use ($from_date, $to_date) {
        //             $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
        //         })
        //         ->when($request->from_date && $request->to_date == null, function ($q) use ($from_date) {
        //             $q->whereDate('bettings.date_time', '>=', $from_date);
        //         })
        //         ->when($request->from_date == null && $request->to_date, function ($q) use ($to_date) {
        //             $q->whereDate('bettings.date_time', '<=', $to_date);
        //         })
        //         ->when($searchInput, function ($q) use ($searchInput) {
        //             $q->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
        //         })
        //         ->groupBy('betting_numbers.number', 'game_settings.twist_multiplier') // Include game_settings.twist_multiplier in the GROUP BY clause
        //         ->orderBy('total_amount', 'desc')
        //         ->get();
        #end

        #modify query
        $groupedBettingNumbers = BettingNumber::selectRaw('
        betting_numbers.number, 
        COUNT(betting_numbers.number) as bets, 
        CAST((SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier)) AS DOUBLE) as betting_multiplier, 
        SUM(betting_numbers.amount) as total_amount,
        IFNULL(
            (
                SELECT SUM(bn.amount)
                FROM betting_numbers bn
                JOIN bettings b ON bn.betting_id = b.id
                WHERE b.game_id = bettings.game_id 
                  AND b.game_setting_id = bettings.game_setting_id 
                  AND (
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) - 1) OR
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) + 1) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1))
                  )
                  AND bn.number != betting_numbers.number
            ), 0)  * game_settings.twist_multiplier AS total_twist_amount 
    ')
            // * game_settings.twist_multiplier
            ->join('bettings', 'betting_numbers.betting_id', '=', 'bettings.id') // Join with the bettings table
            ->join('game_settings', 'bettings.game_setting_id', '=', 'game_settings.id') // Join with the game_settings table
            ->where('bettings.game_id', 2)
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when($request->from_date && $request->to_date, function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when($request->from_date && $request->to_date == null, function ($q) use ($from_date) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when($request->from_date == null && $request->to_date, function ($q) use ($to_date) {
                $q->whereDate('bettings.date_time', '<=', $to_date);
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
            })
            ->groupBy('betting_numbers.number', 'game_settings.twist_multiplier', 'bettings.game_id', 'bettings.game_setting_id') // Include all necessary columns in GROUP BY
            ->orderBy('total_amount', 'desc')
            ->get();

        foreach ($groupedBettingNumbers as $groupedBettingNumber) {
            $total_twist_amount = $groupedBettingNumber->total_twist_amount;   //total amount to twist number;
            $twist_amount = $groupedBettingNumber->twist_amount;
            $grand_total_amount += $groupedBettingNumber->total_amount;
            $total_amount = $groupedBettingNumber->total_amount;
            $total_prize = $total_amount * $groupedBettingNumber->betting_multiplier;
            array_push($bettedNumbersWithCount, [
                'number' => $groupedBettingNumber->number,
                'bets' => $groupedBettingNumber->bets,
                'amount' => $total_amount,
                'total_twist_amount' => $total_twist_amount,
                'twist_amount' => $twist_amount,
                'multiplier' => $groupedBettingNumber->betting_multiplier,
                'total_prize' => $total_prize,
            ]);
        }
        $report = ['bet_numbers' => $bettedNumbersWithCount, 'total_amount' => $grand_total_amount];
        return $report;
    }

    public function getBettingNumberCounts($request)
    {
        // return $this->getBettingNumberCountsOld($request);
        $perPage = $request->per_page ?? config('common.per_page');
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $gameSettingId = $request->game_setting_id;
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
        // dd($grandTotalAmount);
        $groupedBettingNumbers = BettingNumber::selectRaw('
        betting_numbers.number, 
        COUNT(betting_numbers.number) as bets, 
        SUM(betting_numbers.amount) as total_amount,
        game_settings.twist_multiplier,
        game_settings.bet_multiplier as betting_multiplier,
        SUM(betting_numbers.amount) * game_settings.bet_multiplier as total_bingo_amount,
        IFNULL(
            (
                SELECT SUM(bn.amount)
                FROM betting_numbers bn
                JOIN bettings b ON bn.betting_id = b.id
                WHERE b.game_id = bettings.game_id 
                  AND b.game_setting_id = bettings.game_setting_id 
                  AND (
                  (bn.number = "999" AND betting_numbers.number = "000") OR
                    (bn.number = "000" AND betting_numbers.number = "999") OR
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) - 1) OR
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) + 1) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1))
                  )
                  AND bn.number != betting_numbers.number
            ), 0) * game_settings.twist_multiplier AS total_twist_amount ,
            (' . $grandTotalAmount . ' - (SUM(betting_numbers.amount) * game_settings.bet_multiplier + IFNULL(
            (
                SELECT SUM(bn.amount)
                FROM betting_numbers bn
                JOIN bettings b ON bn.betting_id = b.id
                WHERE b.game_id = bettings.game_id 
                  AND b.game_setting_id = bettings.game_setting_id 
                  AND (
                  (bn.number = "999" AND betting_numbers.number = "000") OR
                    (bn.number = "000" AND betting_numbers.number = "999") OR
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) - 1) OR
                    bn.number = CONCAT(LEFT(betting_numbers.number, 2), RIGHT(betting_numbers.number, 1) + 1) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 1, 1), SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1)) OR
                    bn.number = CONCAT(SUBSTRING(betting_numbers.number, 3, 1), SUBSTRING(betting_numbers.number, 2, 1), SUBSTRING(betting_numbers.number, 1, 1))
                  )
                  AND bn.number != betting_numbers.number
            ), 0) * game_settings.twist_multiplier)) AS total_prize
    ')
            ->join('bettings', 'betting_numbers.betting_id', '=', 'bettings.id')
            ->join('game_settings', 'bettings.game_setting_id', '=', 'game_settings.id')
            ->where('bettings.game_id', 2)
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when($request->from_date && $request->to_date, function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when($request->from_date && $request->to_date == null, function ($q) use ($from_date) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when($request->from_date == null && $request->to_date, function ($q) use ($to_date) {
                $q->whereDate('bettings.date_time', '<=', $to_date);
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
            })
            ->groupBy('betting_numbers.number', 'game_settings.bet_multiplier', 'game_settings.twist_multiplier', 'bettings.game_id', 'bettings.game_setting_id')
            ->orderBy('total_amount', 'desc')
            // ->get();
            ->paginate($perPage);

        // Separate query for calculating grand total amount
        // $grandTotalAmount = BettingNumber::selectRaw('SUM(total_amount) as grand_total_amount')
        //     ->join('bettings', 'betting_numbers.betting_id', '=', 'bettings.id')
        //     ->join('game_settings', 'bettings.game_setting_id', '=', 'game_settings.id')
        //     ->where('bettings.game_id', 2)
        //     ->where('bettings.game_setting_id', $gameSettingId)
        //     ->when($request->from_date && $request->to_date, function ($q) use ($from_date, $to_date) {
        //         $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
        //     })
        //     ->when($request->from_date && $request->to_date == null, function ($q) use ($from_date) {
        //         $q->whereDate('bettings.date_time', '>=', $from_date);
        //     })
        //     ->when($request->from_date == null && $request->to_date, function ($q) use ($to_date) {
        //         $q->whereDate('bettings.date_time', '<=', $to_date);
        //     })
        //     ->when($searchInput, function ($q) use ($searchInput) {
        //         $q->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%');
        //     })
        //     ->value('grand_total_amount');
        return $groupedBettingNumbers;

    }

    public function getBettingAmountsByCustomer($request)
    {
        $gameId = 2;
        $gameSettingId = $request->game_setting_id;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $total_amount = DB::table('bettings')
            ->where('game_setting_id', $gameSettingId)
            ->join('customers', 'bettings.customer_id', 'customers.id')
            // ->leftJoin('betting_numbers', function ($join) {
            //     $join->on('bettings.id', '=', 'betting_numbers.betting_id');
            // })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
                        ->where('customers.name', 'LIKE', '%' . $searchInput . '%');
                });
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
            ->where('game_id', $gameId)
            ->sum('total_amount');
        $customersWithBetAmounts = Customer::select(
            'customers.id',
            'customers.name',
            'customers.phone_number',
            DB::raw('COALESCE(SUM(betting_numbers.amount), 0) AS total_betted_amount'),
            DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 0) AS DOUBLE) as betting_multiplier'),
            DB::raw('COUNT(DISTINCT bettings.id) AS total_bettings'),
            DB::raw('ROUND((COALESCE(SUM(betting_numbers.amount), 0) / ' . $total_amount . ') * 100, 2) AS betting_percentage')
        )
            ->join('bettings', function ($join) use ($gameId, $gameSettingId) {
                $join->on('customers.id', '=', 'bettings.customer_id')
                    ->where('bettings.game_id', $gameId)
                    ->where('bettings.game_setting_id', $gameSettingId);
            })
            ->leftJoin('betting_numbers', function ($join) {
                $join->on('bettings.id', '=', 'betting_numbers.betting_id');
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
                        ->where('customers.name', 'LIKE', '%' . $searchInput . '%');
                });
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
            ->groupBy('customers.id', 'customers.name', 'customers.phone_number')
            ->orderByDesc('total_betted_amount')
            ->get();
        // $bettingMultiplier = $gameSetting->bet_multiplier;
        // $twistMultiplier = $gameSetting->twist_multiplier;
        foreach ($customersWithBetAmounts as $customer) {
            $customer['total_bingo_amount'] =
                BettingNumber::where('is_win', 1)
                    ->whereIn('betting_id', function ($query) use ($gameSettingId, $customer, $request, $from_date, $to_date, $searchInput) {
                        $query->select('id')->from('bettings')
                            ->where('customer_id', $customer->id)
                            ->where('game_setting_id', $gameSettingId)
                            ->where('game_id', 2);
                    })->sum('amount') * $customer->betting_multiplier;
            $customer['total_bingo_amount'] +=
                BettingNumber::where('is_win', 1)
                    // ->where('betting_multiplier', $twistMultiplier)
                    ->whereIn('betting_id', function ($query) use ($gameSettingId, $customer, $request, $from_date, $to_date) {
                        $query->select('id')->from('bettings')
                            ->where('customer_id', $customer->id)
                            ->where('game_setting_id', $gameSettingId)
                            ->where('game_id', 2);
                    })->sum('amount') * $customer->betting_multiplier;
        }
        return ['customer_bet_amounts' => $customersWithBetAmounts, 'all_total_betted_amount' => $total_amount];
    }

    public function getBettingCustomers($request)
    {
        $gameSettingId = $request->game_setting_id;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $gameId = 2;

        $bettingCustomers = Customer::select(
            'customers.id',
            'customers.name',
            'customers.phone_number',
            'betting_numbers.number',
            'bettings.date_time',
            'game_settings.lottery_date_time',
            DB::raw('SUM(betting_numbers.amount) AS total_betted_amount')
        )
            ->join('bettings', function ($join) use ($gameId) {
                $join->on('customers.id', '=', 'bettings.customer_id')
                    ->where('game_id', '=', $gameId);
            })
            ->join('betting_numbers', function ($join) {
                $join->on('bettings.id', '=', 'betting_numbers.betting_id');
            })
            ->join('game_settings', 'bettings.game_setting_id', 'game_settings.id')
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');

                });
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
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'betting_numbers.number',
                'bettings.date_time',
                'game_settings.lottery_date_time',
            )

            ->orderByDesc('total_betted_amount')
            ->get();

        return [
            'betting_customers' => $bettingCustomers,
            'date' => null,
            'time' => null,
            // 'date' => date_create($gameSetting->lottery_date_time)->format('Y-m-d'),
            // 'time' => date_create($gameSetting->lottery_date_time)->format('H:i A')
        ];
    }

    public function getBingoCustomers($request)
    {
        $gameId = 2;
        $gameSettingId = $request->game_setting_id;
        $perPage = $request->per_page ?? config('common.per_page');
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $bingoCustomers = Customer::select(
            'customers.id',
            'customers.name',
            'customers.phone_number',
            'betting_numbers.number',
            'bettings.date_time',
            'betting_wins.number as winning_number',
            'betting_numbers.is_twist',
            'game_settings.lottery_date_time',
            DB::raw('SUM(betting_numbers.amount) AS total_betted_amount'),
            DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 2) AS DOUBLE) AS betting_multiplier'),
            DB::raw('CAST(ROUND(SUM(betting_numbers.betting_multiplier) / COUNT(betting_numbers.betting_multiplier), 2) AS DOUBLE) * SUM(betting_numbers.amount) AS bingo_amount')
        )
            ->join('bettings', function ($join) use ($gameId) {
                $join->on('customers.id', '=', 'bettings.customer_id')
                    ->where('game_id', '=', $gameId);
            })
            ->join('betting_wins', function ($join) use ($gameId) {
                $join->on('betting_wins.game_setting_id', '=', 'bettings.game_setting_id')
                    ->where('is_approved', 1);
            })
            ->join('betting_numbers', function ($join) {
                $join->on('bettings.id', '=', 'betting_numbers.betting_id')
                    ->where('betting_numbers.is_win', '=', 1);
            })
            ->join('game_settings', 'bettings.game_setting_id', 'game_settings.id')
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_numbers.number', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');

                });
            })
            ->where('bettings.game_setting_id', $gameSettingId)
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('bettings.date_time', [now(), $to_date]);
            })
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'betting_numbers.number',
                'bettings.date_time',
                'betting_numbers.is_twist',
                'betting_wins.number',
                'game_settings.lottery_date_time',
            )
            ->orderByDesc('total_betted_amount');
        $bingoCustomers = isset($request->per_page) ? $bingoCustomers->paginate($perPage) : $bingoCustomers->get();
        // ->get();

        return [
            'bingo_customers' => $bingoCustomers,
            'date' => null,
            'time' => null,
            // 'date' => date_create($gameSetting->lottery_date_time)->format('Y-m-d'),
            // 'time' => date_create($gameSetting->lottery_date_time)->format('H:i A')

        ];
    }
}
