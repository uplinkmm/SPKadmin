<?php
namespace App\Repositories\SlotTransaction;

use DateInterval;

use App\Models\Wager;

use App\Models\Customer;
use App\Models\GameSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SeamlessTransaction;
use Illuminate\Pagination\LengthAwarePaginator;

class SlotTransactionRepository implements SlotTransactionInterface
{
    public function index($request)
    {
        $perPage = (int) $request->per_page ?? 50;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        //     return SeamlessTransaction::orderByDesc('seamless_transactions.id')
        //         ->whereNotNull('wager_id')
        //         ->join('customers', 'seamless_transactions.customer_id', 'customers.id')
        //         ->join('wagers', 'seamless_transactions.wager_id', 'wagers.id')
        //         ->join('products', 'seamless_transactions.product_id', 'products.id')
        //         ->join('game_types', 'seamless_transactions.game_type_id', 'game_types.id')
        //         ->join('seamless_events', 'seamless_transactions.seamless_event_id', 'seamless_events.id')
        //         ->select(
        //             'seamless_transactions.id',
        //             'customers.name',
        //             'customers.phone_number',
        //             'seamless_transactions.bet_amount',
        //             'seamless_transactions.transaction_amount',
        //             'products.name as game_name',
        //             'game_types.name as site_name',
        //             'seamless_transactions.created_at',
        //             'seamless_events.message_id as ref_no',
        //             DB::raw('seamless_transactions.transaction_amount - seamless_transactions.bet_amount AS profit'),
        //             DB::raw("
        //     CASE 
        //         WHEN (seamless_transactions.transaction_amount - seamless_transactions.bet_amount) < 0 THEN 'lose'
        //         ELSE 'win'
        //     END AS win_or_lose
        // ")
        //         )
        //         ->when(isset($request->game_type_id) && $request->game_type_id, function ($q) use ($request) {
        //             $q->where('game_types.id', $request->game_type_id);
        //         })
        //         ->when(isset($request->product_id) && $request->product_id, function ($q) use ($request) {
        //             $q->where('products.id', $request->product_id);
        //         })
        //         ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
        //             $q->whereBetween(DB::raw('DATE(seamless_transactions.created_at)'), [$from_date, $to_date]);
        //         })
        //         ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
        //             $q->whereDate('seamless_transactions.created_at', '>=', $from_date);
        //         })
        //         ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
        //             $q->whereBetween('seamless_transactions.created_at', [now(), $to_date]);
        //         })
        //         ->when(($request->from_date == null && $request->to_date == null), function ($q) {
        //             $q->whereDate('seamless_transactions.created_at', '>=', now()->format('Y-m-d'));
        //         })
        //         ->paginate($perPage);

        $transactions = DB::table('seamless_events')
            ->join('seamless_transactions', 'seamless_events.id', 'seamless_transactions.seamless_event_id')
            ->join('customers', 'seamless_transactions.customer_id', 'customers.id')
            ->join('products', 'seamless_transactions.product_id', 'products.id')
            ->join('game_types', 'seamless_transactions.game_type_id', 'game_types.id')
            ->selectRaw("
            seamless_events.id,
            seamless_transactions.id as seamless_transaction_id,
            seamless_transactions.game_type_id as game_type_id,
            seamless_events.message_id as ref_no,
            customers.name,
            customers.phone_number,
            game_types.name AS game_name,
        products.name AS site_name,
             JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].BetAmount')) AS bet_amount,
        JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].PayoutAmount')) AS transaction_amount,
        JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].PayoutAmount')) - 
        JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].BetAmount')) AS profit,
        CASE 
            WHEN (JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].PayoutAmount')) - 
                  JSON_UNQUOTE(JSON_EXTRACT(raw_data, '$.Transactions[0].BetAmount'))) > 0 
            THEN 'win' 
            ELSE 'lose' 
        END AS win_or_lose
    ")
            ->whereRaw("JSON_CONTAINS(JSON_EXTRACT(raw_data, '$.Transactions'), '{\"Status\": 101}', '$')")
            // ->when(isset($request->game_type_id) && $request->game_type_id, function ($q) use ($request) {
            //     $q->where('game_types.id', $request->game_type_id);
            // })
            // ->when(isset($request->product_id) && $request->product_id, function ($q) use ($request) {
            //     $q->where('products.id', $request->product_id);
            // })
            // ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
            //     $q->whereBetween(DB::raw('DATE(seamless_events.request_time)'), [$from_date, $to_date]);
            // })
            // ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
            //     $q->whereDate('seamless_events.request_time', '>=', $from_date);
            // })
            // ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
            //     $q->whereBetween('seamless_events.request_time', [now(), $to_date]);
            // })
            // ->when(($request->from_date == null && $request->to_date == null), function ($q) {
            //     $q->whereDate('seamless_events.request_time', '>=', now()->format('Y-m-d'));
            // })
            ->orderBy('seamless_events.id', 'desc')
            ->paginate($perPage);
        return $transactions;


        // return SeamlessTransaction::whereNotNull('wager_id')
        //     ->join('customers', 'seamless_transactions.customer_id', 'customers.id')
        //     ->join('wagers', 'seamless_transactions.wager_id', 'wagers.id')
        //     ->join('products', 'seamless_transactions.product_id', 'products.id')
        //     ->join('game_types', 'seamless_transactions.game_type_id', 'game_types.id')
        //     ->join('seamless_events', 'seamless_transactions.seamless_event_id', 'seamless_events.id')
        //     ->select(
        //         'seamless_transactions.wager_id',
        //         'customers.name',
        //         'customers.phone_number',
        //         DB::raw('SUM(seamless_transactions.transaction_amount) AS transaction_amount'),
        //         DB::raw('MAX(seamless_transactions.bet_amount) AS bet_amount'),
        //         'products.name as game_name',
        //         'wagers.seamless_wager_id as ref_no',
        //         'game_types.name as site_name',
        //         // DB::raw('MAX(wagers.created_at) AS created_at'),
        //         DB::raw('SUM(seamless_transactions.transaction_amount) - MAX(seamless_transactions.bet_amount)  AS profit'), // Fixed profit calculation
        //         DB::raw("
        //     CASE 
        //         WHEN (MAX(seamless_transactions.bet_amount) - SUM(seamless_transactions.transaction_amount)) < 0 THEN 'lose'
        //         ELSE 'win'
        //     END AS win_or_lose
        // ")
        //     )
        //     ->when(isset($request->game_type_id) && $request->game_type_id, function ($q) use ($request) {
        //         $q->where('game_types.id', $request->game_type_id);
        //     })
        //     ->when(isset($request->product_id) && $request->product_id, function ($q) use ($request) {
        //         $q->where('products.id', $request->product_id);
        //     })
        //     ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
        //         $q->whereBetween(DB::raw('DATE(seamless_events.request_time)'), [$from_date, $to_date]);
        //     })
        //     ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
        //         $q->whereDate('seamless_events.request_time', '>=', $from_date);
        //     })
        //     ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
        //         $q->whereBetween('seamless_events.request_time', [now(), $to_date]);
        //     })
        //     ->when(($request->from_date == null && $request->to_date == null), function ($q) {
        //         $q->whereDate('seamless_events.request_time', '>=', now()->format('Y-m-d'));
        //     })
        //     ->groupBy('seamless_transactions.wager_id', 'customers.name', 'customers.phone_number', 'products.name', 'game_types.name')
        //     ->orderByDesc(DB::raw('MAX(seamless_events.request_time)'))
        //     ->paginate($perPage);

    }


    public function slotProviderReport($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $perPage = (int) $request->per_page ?? 50;
        return SeamlessTransaction::join('customers', 'seamless_transactions.customer_id', '=', 'customers.id')
            ->join('wagers', 'seamless_transactions.wager_id', '=', 'wagers.id')
            ->join('products', 'seamless_transactions.product_id', '=', 'products.id')
            ->join('game_types', 'seamless_transactions.game_type_id', '=', 'game_types.id')
            ->join('seamless_events', 'seamless_transactions.seamless_event_id', '=', 'seamless_events.id')
            ->whereNotNull('wager_id')
            ->select(
                'products.name as game_name',
                // DB::raw('DATE(seamless_transactions.created_at) as transaction_date'), // Group by DATE instead
                DB::raw('SUM(seamless_transactions.bet_amount) AS total_bet_amount'),
                DB::raw('SUM(seamless_transactions.transaction_amount) AS total_transaction_amount'),
                DB::raw('SUM(seamless_transactions.transaction_amount) - SUM(seamless_transactions.bet_amount) AS total_profit')
            )
            ->groupBy('products.id', 'products.name') // Group by DATE
            ->orderByDesc('total_bet_amount') // Ordering by an aggregate value is safe
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(seamless_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->paginate($perPage);
    }

    public function slotUserReport($request)
    {
        $from_date = isset($request->from_date) || $request->from_date != null ? convertDateFormat($request->from_date) : null;
        $to_date = isset($request->to_date) || $request->to_date != null ? convertDateFormat($request->to_date) : null;
        $perPage = $request->per_page ?? 50;
        return SeamlessTransaction::join('customers', 'seamless_transactions.customer_id', '=', 'customers.id')
            ->join('wagers', 'seamless_transactions.wager_id', '=', 'wagers.id')
            ->join('products', 'seamless_transactions.product_id', '=', 'products.id')
            ->join('game_types', 'seamless_transactions.game_type_id', '=', 'game_types.id')
            ->join('seamless_events', 'seamless_transactions.seamless_event_id', '=', 'seamless_events.id')
            ->select(
                'customers.name as customer_name',
                // 'seamless_transactions.created_at',
                DB::raw('SUM(seamless_transactions.bet_amount) AS total_bet_amount'),
                DB::raw('SUM(seamless_transactions.transaction_amount) AS total_transaction_amount'),
                DB::raw('SUM(seamless_transactions.transaction_amount) - SUM(seamless_transactions.bet_amount) AS total_profit')
            )
            ->groupBy('customers.id', 'customers.name', )
            ->orderByDesc('total_bet_amount') // Optional: Order by total bet amount
            ->when($request->search_input, function ($query) use ($request) {
                $query->where('customers.name', 'LIKE', '%' . $request->search_input . '%');
                // ->orWhere('customers.phone_number','LIKE','%'.$request->search_input.'%');
            })
            ->when(((isset($request->from_date) && $from_date) && (isset($request->from_date) && $to_date)), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(seamless_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('seamless_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('seamless_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('seamless_transactions.created_at', today());
            })

            ->whereNotNull('wager_id')
            ->paginate($perPage);
    }

    public function slotUserList($request)
    {
        $perPage = (int) $request->per_page ?? 20;
        $customers = Customer::where('is_verified', 1)
            ->when($request->search_input, function ($query) use ($request) {
                $query->where('customers.name', 'LIKE', '%' . $request->search_input . '%');
                // ->orWhere('customers.phone_number','LIKE','%'.$request->search_input.'%');
            })
            ->paginate($perPage);
        $transformCustomer = $customers->getCollection()->transform(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone_number' => $customer->phone_number,
                'game_money_balance' => $customer->balanceFloat, // Accessor value
                'verified_at' => $customer->verified_at,
            ];
        });
        $paginatedCustomers = new LengthAwarePaginator(
            $transformCustomer,                // Items (transformed collection)
            $customers->total(),               // Total items
            $perPage,             // Items per page
            $customers->currentPage(),         // Current page
            ['path' => $customers->path()]     // Pagination path
        );
        return $paginatedCustomers;
    }
}