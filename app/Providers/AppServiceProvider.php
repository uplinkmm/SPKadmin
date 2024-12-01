<?php

namespace App\Providers;

use App\Models\AgentWithdrawalTransaction;
use App\Models\WalletTransaction;
use App\Observers\AgentWithdrawalObserver;
use Illuminate\Support\ServiceProvider;
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
        ]);
    }
}
