<?php

namespace App\Repositories\WalletTransaction;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletTransactionRepository implements WalletTransactionInterface
{
    public function getBalanceTransaction($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $customerId = $request->customer_id;
        $searchInput = $request->search_input;
        $perPage = $request->per_page ?? config('common.per_page');
        // ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
        //     $q->whereBetween(DB::raw('DATE(sub.date_time)'), [$from_date, $to_date]);
        // })
        // ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
        //     $q->whereDate('sub.date_time', '>=', $from_date);
        // })
        // ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
        //     $q->whereBetween('sub.date_time', [now(), $to_date]);
        // })
        // ->when(($from_date == null && $to_date == null), function ($q) {
        //     $q->whereDate('sub.date_time', '>=', now()->format('Y-m-d'));
        // })
        // ->when(($customerId!=0 && $customerId!='0'),function($q) use($customerId){
        //     $q->where('sub.customer_id',$customerId);
        // })
        // ->when($searchInput, function ($q) use ($searchInput) {
        //     $q->where(function ($query) use ($searchInput) {
        //         $query->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
        //         ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%');
        //     });
        // })
        // $subquery = DB::table('wallet_transactions')
        //     ->select(
        //         'id',
        //         'customer_id',
        //         'date_time',
        //         'walletable_type',
        //         'walletable_id',
        //         'action',
        //         'amount',
        //         DB::raw('SUM(CASE WHEN action = "in" THEN amount ELSE -amount END) OVER (PARTITION BY customer_id ORDER BY date_time, id) AS current_amount')
        //     );

        // // Main query to calculate previous_amount using LAG() on the subquery result
        // $transactions = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
        //     ->mergeBindings($subquery) // Correct way to merge bindings without getQuery()
        //     ->join('customers', 'sub.customer_id', '=', 'customers.id') // Join with customers table
        //     ->leftJoin('bettings', function($join) {
        //         $join->on('sub.walletable_id', '=', 'bettings.id')
        //              ->where('sub.walletable_type', '=', 'betting'); // Join with bettings table when walletable_type is 'betting'
        //     })
        //     ->leftJoin('games', 'bettings.game_id', '=', 'games.id') // 
        //     ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
        //         $q->whereBetween(DB::raw('DATE(sub.date_time)'), [$from_date, $to_date]);
        //     })
        //     ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
        //         $q->whereDate('sub.date_time', '>=', $from_date);
        //     })
        //     ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
        //         $q->whereBetween('sub.date_time', [now(), $to_date]);
        //     })
        //     ->when(($from_date == null && $to_date == null), function ($q) {
        //         $q->whereDate('sub.date_time', '>=', now()->format('Y-m-d'));
        //     })
        //     ->when(($customerId!=0 && $customerId!='0'),function($q) use($customerId){
        //         $q->where('sub.customer_id',$customerId);
        //     })
        //     ->when($searchInput, function ($q) use ($searchInput) {
        //         $q->where(function ($query) use ($searchInput) {
        //             $query->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
        //             ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%');
        //         });
        //     })
        //     ->select(
        //         'sub.id',
        //         'sub.action',
        //         'customers.id as customer_id',
        //         'customers.name',
        //         'customers.phone_number',
        //         'sub.date_time',
        //         'walletable_type',
        //         'amount',
        //         'current_amount',
        //         DB::raw('LAG(current_amount, 1, 0) OVER (PARTITION BY sub.customer_id ORDER BY sub.date_time, id) AS previous_amount'),
        //         DB::raw('IF(sub.walletable_type = "betting", games.name, NULL) AS description') // Conditionally get game name
        //     )
        //     ->orderBy('sub.date_time','desc') // Only order by date_time
        //     ->orderBy('sub.id','desc') // Optional: to ensure consistent ordering for transactions on the same date_time
        //     ->get();

        #correct
//             $subquery = DB::table('wallet_transactions')
//         ->select(
//             'id',
//             'customer_id',
//             'date_time',
//             'walletable_type',
//             'walletable_id',
//             'action',
//             'amount',
//             DB::raw('SUM(CASE WHEN action = "in" THEN amount ELSE -amount END) OVER (PARTITION BY customer_id ORDER BY date_time, id) AS current_amount')
//         );

        //         // Main query to calculate previous_amount using LAG() on the subquery result
// $transactions = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
//     ->mergeBindings($subquery) // Correct way to merge bindings
//     ->join('customers', 'sub.customer_id', '=', 'customers.id') // Join with customers table
//     ->leftJoin('bettings', function($join) {
//         $join->on('sub.walletable_id', '=', 'bettings.id')
//              ->where('sub.walletable_type', '=', 'betting'); // Join with bettings table when walletable_type is 'betting'
//     })
//     ->leftJoin('games', 'bettings.game_id', '=', 'games.id') // Join with games table to get the game name
//     ->when($from_date && $to_date, function ($q) use ($from_date, $to_date) {
//         $q->whereBetween('sub.date_time', [$from_date, $to_date]);
//     })
//     ->when($from_date && !$to_date, function ($q) use ($from_date) {
//         $q->whereDate('sub.date_time', '>=', $from_date);
//     })
//     ->when(!$from_date && $to_date, function ($q) use ($to_date) {
//         $q->whereDate('sub.date_time', '<=', $to_date);
//     })
//     ->when(!$from_date && !$to_date, function ($q) {
//         $q->whereDate('sub.date_time', '>=', now()->format('Y-m-d'));
//     })
//     ->when($customerId != 0 && $customerId != '0', function($q) use($customerId) {
//         $q->where('sub.customer_id', $customerId);
//     })
//     ->when($searchInput, function ($q) use ($searchInput) {
//         $q->where(function ($query) use ($searchInput) {
//             $query->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
//                 ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%');
//         });
//     })
//     ->select(
//         'sub.id',
//         'sub.action',
//         'customers.id as customer_id',
//         'customers.name',
//         'customers.phone_number',
//         'sub.date_time',
//         'sub.walletable_type',
//         'sub.amount',
//         'sub.current_amount',
//         DB::raw('LAG(sub.current_amount, 1, 0) OVER (PARTITION BY sub.customer_id ORDER BY sub.date_time, sub.id) AS previous_amount'),
//         DB::raw('CASE WHEN sub.walletable_type = "betting" THEN games.name ELSE NULL END AS description') // Use CASE instead of IF
//     )
//     ->orderBy('sub.date_time', 'desc') // Order by date_time in descending order
//     ->orderBy('sub.id', 'desc') // Order by id in descending order
//     ->paginate($perPage);
        #end
        $subquery = DB::table('wallet_transactions')
            ->select(
                'id',
                'customer_id',
                'date_time',
                'walletable_type',
                'walletable_id',
                'action',
                'amount',
                DB::raw('SUM(CASE WHEN action = "in" THEN amount ELSE -amount END) OVER (PARTITION BY wallet_transactions.customer_id ORDER BY wallet_transactions.date_time, id) AS current_amount')
            );

        $transactions = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->join('customers', 'sub.customer_id', '=', 'customers.id')
            ->leftJoin('bettings', function ($join) {
                $join->on('sub.walletable_id', '=', 'bettings.id')
                    ->where('sub.walletable_type', '=', 'betting');
            })
            ->leftJoin('cash_withdrawl_transactions', function ($join) {
                $join->on('sub.walletable_id', '=', 'cash_withdrawl_transactions.id')
                    ->where('sub.walletable_type', '=', 'cash_withdrawl_transaction');
            })
            ->leftJoin('games', 'bettings.game_id', '=', 'games.id')
            ->select(
                'sub.id',
                'sub.action',
                'customers.id as customer_id',
                'customers.name',
                'customers.phone_number',
                'sub.date_time',
                'sub.walletable_type',
                'sub.walletable_id',
                'sub.amount',
                'sub.current_amount',
                DB::raw('(
            SELECT 
                SUM(CASE WHEN action = "in" THEN amount ELSE -amount END)
            FROM 
                wallet_transactions as wt
            WHERE 
                wt.customer_id = sub.customer_id 
                AND wt.date_time <= sub.date_time 
                AND wt.id < sub.id
            ) AS previous_amount'
                ),
                DB::raw('CASE WHEN sub.walletable_type = "cash_withdrawl_transaction" THEN cash_withdrawl_transactions.status ELSE NULL END AS withdrawal_status'),
                DB::raw('CASE WHEN sub.walletable_type = "betting" THEN games.name ELSE NULL END AS description')
            )
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween(DB::raw('DATE(sub.date_time)'), [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function ($query) use ($from_date) {
                $query->whereDate('sub.date_time', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function ($query) use ($to_date) {
                $query->whereDate('sub.date_time', '<=', $to_date);
            })
            ->when(!$from_date && !$to_date, function ($query) {
                $query->whereDate('sub.date_time', '>=', now()->format('Y-m-d'));
            })
            ->when($customerId != 0 && $customerId != '0', function ($query) use ($customerId) {
                $query->where('sub.customer_id', $customerId);
            })
            ->when($searchInput, function ($query) use ($searchInput) {
                $query->where(function ($subquery) use ($searchInput) {
                    $subquery->where('customers.phone_number', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.name', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->orderBy('sub.date_time', 'desc')
            ->orderBy('sub.id', 'desc')
            // ->orderBy('sub.date_time')
            // ->orderBy('sub.id')
            ->paginate($perPage);
        return $transactions;
    }

}