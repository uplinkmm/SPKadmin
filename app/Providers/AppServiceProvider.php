<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\WalletTransaction;
use App\Observers\CustomerObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\AgentWithdrawalTransaction;
use App\Observers\AgentWithdrawalObserver;
use App\Observers\WalletTransactionObserver;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        WalletTransaction::observe(WalletTransactionObserver::class);
        AgentWithdrawalTransaction::observe(AgentWithdrawalObserver::class);
        Customer::observe(CustomerObserver::class);
        Relation::enforceMorphMap([
            'topup_transaction' => 'App\Models\TopupTransaction',
            'cash_withdrawl_transaction' => 'App\Models\CashWithdrawlTransaction',
            'user' => 'App\Models\User',
            'betting' => 'App\Models\Betting',
            'betting_number' => 'App\Models\BettingNumber',
            'betting_win' => 'App\Models\BettingWin',
            'customer' => 'App\Models\Customer',
            'agent' => 'App\Models\Agent',
            'agent_withdrawal_transaction'=>'App\Models\AgentWithdrawalTransaction',
            'twist_win_number'=>'App\Models\TwistWinNumber',
            'wallet' => 'App\Models\Wallet',
            'wallet_transfer' => 'App\Models\WalletTransfer',
            'ads'=>'App\Models\Ads',
            'lottery_winning_number'=>'App\Models\LotteryWinningNumber',
            'game_promotion' => 'App\Models\GamePromotion',
            'user_promotion' => 'App\Models\UserPromotion',
            'referral_promotion' => 'App\Models\ReferralPromotion'
        ]);
    }
}
