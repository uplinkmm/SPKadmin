<?php

namespace App\Repositories\Dashboard;

use stdClass;
use Exception;
use App\Models\Game;
use App\Models\Customer;
use App\Models\GameSetting;
use App\Models\CustomerWallet;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardInterface
{

    public function getDashboardCRN($request)
    {
        $dashboard_crn = new stdClass();
        $dashboard_crn->dashboard = $this->getDashboard();
        $dashboard_crn->system_control = $this->getGame();
        $dashboard_crn->transaction_control = $this->getTransactionType();
        $dashboard_crn->threed_setting = $this->getThreedSetting();
        $dashboard_crn->twod_games=$this->getTwoDGame();
        return $dashboard_crn;
    }

    public function getTwoDGame(){
        return Game::where('type','2d')
        ->where('is_active',1)
        ->get();
    }

    public function getDashboard()
    {
        $dashboard = CustomerWallet::join('customers', 'customer_wallets.customer_id', '=', 'customers.id')
            ->leftJoin('topup_transactions', function ($join) {
                $join->on('customer_wallets.id', '=', 'topup_transactions.customer_id')
                    ->where('topup_transactions.status', '=', 'confirmed');
            })
            ->leftJoin('cash_withdrawl_transactions', function ($join) {
                $join->on('customer_wallets.id', '=', 'cash_withdrawl_transactions.customer_id')
                    ->where('cash_withdrawl_transactions.status', '=', 'confirmed');
            })
            ->select(
                DB::raw('SUM( distinct customer_wallets.balance) as total_wallet_balance'),
                DB::raw('(SELECT COUNT(*) FROM customers WHERE is_verified = 1) as total_customer'),
                // DB::raw('(SELECT COUNT(*) FROM customers WHERE is_verified = 1) as active_customer'),
                DB::raw('(SELECT COUNT(*) FROM customers WHERE is_verified = 1 AND EXISTS (SELECT 1 FROM customer_wallets cw WHERE cw.customer_id = customers.id AND cw.balance > 0)) as active_customer'),
                DB::raw('COALESCE(SUM(DISTINCT topup_transactions.amount), 0) as total_topup_amount'),
                DB::raw('COALESCE(SUM( DISTINCT cash_withdrawl_transactions.amount), 0) as total_withdrawl_amount')
            )
            ->first();
        return $dashboard;
    }
    public function getGame()
    {
        // $gameSetting=GameSetting::orderBy('game_settings.id', 'ASC')
        // ->join('games','game_settings.game_id','games.id')
        // ->select(
        //     'game_settings.name',
        //     'opening_time',
        //     'closing_time',
        //     'lottery_time',
        //     'bet_multiplier',
        //     'twist_multiplier',
        //     'closing_amount',
        //     'min',
        //     'max',
        //     'time_status',
        //     'game_id',
        //     'game_settings.is_active',
        //     'opening_date_time',
        //     'closing_date_time',
        //     'lottery_date_time',
        //     'games.type',
        //     )
        // ->get();
        // return $gameSetting;

        $twoDGameSetting = GameSetting::select(
            'game_settings.id',
            'game_settings.name',
            'opening_time',
            'closing_time',
            'lottery_time',
            'bet_multiplier',
            'twist_multiplier',
            'closing_amount',
            'min',
            'max',
            'time_status',
            'game_id',
            'game_settings.is_active',
            // 'opening_date_time',
            // 'closing_date_time',
            'lottery_date_time',
            'games.type',
            'games.name as game_name',
        ) // Replace with your specific columns
            ->join('games', 'game_settings.game_id', 'games.id')
            ->where('games.type', '2d')
            ->orderBy('game_settings.game_id', 'ASC') // First sort by game_id to group them
            ->orderBy('game_settings.lottery_time', 'ASC')
            ->get();

        // Retrieve specific columns when game_id == 2
        $threeDGameSetting = GameSetting::select(
            'game_settings.id',
            'game_settings.name',
            // 'opening_time',
            // 'closing_time',
            // 'lottery_time',
            'bet_multiplier',
            'twist_multiplier',
            'closing_amount',
            'min',
            'max',
            'time_status',
            'game_id',
            'game_settings.is_active',
            'opening_date_time',
            'closing_date_time',
            'lottery_date_time',
            'games.type',
            'games.name as game_name',
        ) // Replace with your specific column
            ->join('games', 'game_settings.game_id', 'games.id')
            ->where('games.type', '3d')
            ->orderBy('game_settings.id', 'ASC')
            ->get();
        // Combine the results into a single collection
        $combinedGameSettings = $twoDGameSetting->concat($threeDGameSetting);
        return $combinedGameSettings;

        #old 
        // $games = Game::orderBy('games.id', 'asc')
        //     ->with('gameSetting')
        // // ->join('game_settings','games.id','game_settings.game_id')
        //     ->get();
        // foreach ($games as $game) {
        //     $game->type = 'MM';
        //     if ($game->id == 1) {
        //         $game->name = 'Two';
        //         $morning = new stdClass();
        //         $evening = new stdClass();
        //         foreach ($game->gameSetting as $setting) {
        //             if ($setting->time_status == 'morning') {
        //                 $morning->betting_multiplier = $setting->bet_multiplier;
        //                 $morning->id = $setting->id;
        //             }
        //             if ($setting->time_status == 'evening') {
        //                 $evening->betting_multiplier = $setting->bet_multiplier;
        //                 $evening->id = $setting->id;
        //             }
        //         }
        //         $game->twod_morning = $morning;
        //         $game->twod_evening = $evening;
        //     }
        //     if ($game->id == 2) {
        //         $game->name = 'Three';
        //         $threed = new stdClass();
        //         if($game->gameSettings){
        //             $threed->betting_multiplier = $game->gameSettings->bet_multiplier;
        //             $threed->twist_multiplier = $game->gameSettings->twist_multiplier;
        //             $threed->id = $game->gameSettings->id;
        //         }else{
        //             $threed->betting_multiplier = 0;
        //             $threed->twist_multiplier = 0;
        //             $threed->id = null;
        //         }
        //         $game->threed=$threed;

        //         unset($game['gameSettings']);
        //     }
        //     unset($game['gameSetting']);
        // }
        // return $games;
        #old end
    }

    public function getTransactionType()
    {
        return TransactionType::orderBy('id', 'ASC')->get();
    }

    public function getFinancialReport($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $transactions = DB::table('wallet_transactions')
            ->select(
                DB::raw('IFNULL(topup_transactions.account_id, cash_withdrawl_transactions.account_id) as account_id'),
                'accounts.name as account_name',
                'accounts.account_type',
                DB::raw('SUM(CASE WHEN  wallet_transactions.walletable_type = "topup_transaction" THEN wallet_transactions.amount ELSE 0 END) as total_topup_amount'),
                DB::raw('SUM(CASE WHEN wallet_transactions.walletable_type = "cash_withdrawl_transaction" THEN wallet_transactions.amount ELSE 0 END) as total_withdrawal_amount'),
                DB::raw('COUNT(CASE WHEN wallet_transactions.walletable_type = "topup_transaction" THEN 1 END) as total_topup_count'),
                DB::raw('COUNT(CASE WHEN wallet_transactions.walletable_type = "cash_withdrawl_transaction" THEN 1 END) as total_withdrawal_count')
            )
            ->leftJoin('topup_transactions', function ($join) {
                $join->on('wallet_transactions.walletable_id', '=', 'topup_transactions.id')
                    ->where('wallet_transactions.walletable_type', '=', 'topup_transaction');
                // ->where('topup_transactions.status', '=', 'confirmed');
            })
            ->leftJoin('cash_withdrawl_transactions', function ($join) {
                $join->on('wallet_transactions.walletable_id', '=', 'cash_withdrawl_transactions.id')
                    ->where('wallet_transactions.walletable_type', '=', 'cash_withdrawl_transaction');
                // ->where('cash_withdrawl_transactions.status', '=', 'confirmed');

            })
            ->leftJoin('accounts', function ($join) {
                $join->on('accounts.id', '=', DB::raw('IFNULL(topup_transactions.account_id, cash_withdrawl_transactions.account_id)'));
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(wallet_transactions.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('wallet_transactions.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('wallet_transactions.date_time', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('wallet_transactions.date_time', '>=', now()->format('Y-m-d'));
            })
            ->groupBy('accounts.account_type', 'account_id', 'accounts.name')
            ->get();
        return $transactions;
    }

    public function getThreedSetting()
    {
        return GameSetting::where('game_id', config('3d_setting.game_id'))
            ->latest()
            ->select('id','opening_date_time', 'closing_date_time', 'lottery_date_time', 'updated_at','game_id')
            ->first();
    }

    public function updateDashboardData($request)
    {

        if ($request->type == 'transaction_control') {
            return $this->updateTransactionType($request);
        }
        if ($request->type == 'system_control') {
            return $this->updateGameSetting($request);
        }

    }

    public function updateTransactionType($request)
    {
        try {
            DB::beginTransaction();
            $column = $request->column;
            $transactionType = TransactionType::find($request->id);
            $transactionType->$column = $request->value;
            $transactionType->save();
            DB::commit();
            return $transactionType;
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            return false;
        }
    }

    public function updateGameSetting($request)
    {
        try {
            DB::beginTransaction();
            $column = $request->column;
            // if ($request->column == 'min' || $request->column == 'max' || $request->column == 'is_active') {
            //     $game = Game::find($request->id);
            //     $game->$column = $request->value;
            //     $game->save();
            // }
            // if ($request->column == 'bet_multiplier' || $request->column == 'twist_multiplier') {
            //     $game = GameSetting::find($request->id);
            //     $game->$column = $request->value;
            //     $game->save();
            // }
            $game = GameSetting::find($request->id);
            $game->$column = $request->value;
            $game->save();
            DB::commit();
            return $game;
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            return false;
        }
    }

    public function getCustomerList($request){
        $customers = CustomerWallet::join('customers', 'customer_wallets.customer_id', '=', 'customers.id')
            ->leftJoin('topup_transactions', function ($join) {
                $join->on('customer_wallets.customer_id', '=', 'topup_transactions.customer_id')
                    ->where('topup_transactions.status', '=', 'confirmed');
            })
            ->leftJoin('cash_withdrawl_transactions', function ($join) {
                $join->on('customer_wallets.customer_id', '=', 'cash_withdrawl_transactions.customer_id')
                    ->where('cash_withdrawl_transactions.status', '=', 'confirmed');
            })
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'customers.verified_at',
                DB::raw('COALESCE(customer_wallets.balance, 0) as wallet_balance'),
                DB::raw('CASE WHEN COALESCE(customer_wallets.balance, 0) > 0 THEN 1 ELSE 0 END as is_active'),
                DB::raw('COALESCE(SUM(topup_transactions.amount), 0) as total_topup_amount'),
                DB::raw('COALESCE(COUNT(topup_transactions.id), 0) as total_topup_count'),
                DB::raw('COALESCE(SUM(cash_withdrawl_transactions.amount), 0) as total_withdrawal_amount'),
                DB::raw('COALESCE(COUNT(cash_withdrawl_transactions.id), 0) as total_withdrawal_count')
            )
            ->groupBy('customers.id', 'customers.name', 'customer_wallets.balance')
            ->paginate(20);

        return $customers;
    }

    public function getCustomerLimitationList($request){
        $customers=Customer::select('id','name','phone_number','two_d_limit','three_d_limit')->paginate(20);
        return $customers;
    }
}
