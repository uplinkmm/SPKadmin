<?php

namespace App\Providers;

use App\Repositories\Ads\AdsInterface;
use App\Repositories\Ads\AdsRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Game\GameInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\Game\GameRepository;
use App\Repositories\User\UserRepository;

use App\Repositories\Agent\AgentInterface;
use App\Repositories\Agent\AgentRepository;

use App\Repositories\Account\AccountInterface;
use App\Repositories\Account\AccountRepository;

use App\Repositories\Customer\CustomerInterface;
use App\Repositories\Customer\CustomerRepository;

use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Dashboard\DashboardRepository;

use App\Repositories\TwoDReport\TwoDReportRepository;
use App\Repositories\Notification\NotificationInterface;

use App\Repositories\BettingWin\TwoDBettingWinRepository;
use App\Repositories\Notification\NotificationRepository;

use App\Repositories\ThreeDReport\ThreeDReportRepository;
use App\Repositories\BettingWin\ThreeDBettingWinRepository;

use App\Repositories\CustomerMoney\CustomerMoneyRepository;
use App\Repositories\TwoDReport\TwoDReportRepositoryInterface;

use App\Repositories\TopupTransaction\TopupTransactionRepository;
use App\Repositories\BettingWin\TwoDBettingWinRepositoryInterface;

use App\Repositories\ThreeDReport\ThreeDReportRepositoryInterface;
use App\Repositories\WalletTransaction\WalletTransactionInterface;
use App\Repositories\ThreeDGameSetting\ThreeDGameSettingRepository;
use App\Repositories\TwoDClosingNumber\TwoDClosingNumberRepository;
use App\Repositories\WalletTransaction\WalletTransactionRepository;
use App\Repositories\BettingWin\ThreeDBettingWinRepositoryInterface;
use App\Repositories\CustomerMoney\CustomerMoneyRepositoryInterface;
use App\Repositories\TopupTransaction\TopupTransactionRepositoryInterface;
use App\Repositories\ThreeDGameSetting\ThreeDGameSettingRepositoryInterface;
use App\Repositories\TwoDClosingNumber\TwoDClosingNumberRepositoryInterface;
use App\Repositories\CashWithdrawlTransaction\CashWithdrawlTransactionRepository;
use App\Repositories\AgentWithdrawalTransaction\AgentWithdrawalTransactionInterface;
use App\Repositories\AgentWithdrawalTransaction\AgentWithdrawalTransactionRepository;
use App\Repositories\CashWithdrawlTransaction\CashWithdrawlTransactionRepositoryInterface;

use App\Repositories\TwoDResult\TwoDResultRepositoryInterface;
use App\Repositories\TwoDResult\TwoDResultRepository;

use App\Repositories\ThreeDResult\ThreeDResultRepositoryInterface;
use App\Repositories\ThreeDResult\ThreeDResultRepository;

use App\Repositories\DreamNumber\DreamNumberRepositoryInterface;
use App\Repositories\DreamNumber\DreamNumberRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->app->bind(TwoDResultRepositoryInterface::class, TwoDResultRepository::class);
        $this->app->bind(ThreeDResultRepositoryInterface::class, ThreeDResultRepository::class);
        $this->app->bind(DreamNumberRepositoryInterface::class, DreamNumberRepository::class);

        $this->app->bind(CustomerMoneyRepositoryInterface::class, CustomerMoneyRepository::class);
        $this->app->bind(TopupTransactionRepositoryInterface::class, TopupTransactionRepository::class);
        $this->app->bind(CashWithdrawlTransactionRepositoryInterface::class, CashWithdrawlTransactionRepository::class);
        $this->app->bind(TwoDReportRepositoryInterface::class, TwoDReportRepository::class);
        $this->app->bind(TwoDBettingWinRepositoryInterface::class, TwoDBettingWinRepository::class);
        $this->app->bind(TwoDClosingNumberRepositoryInterface::class, TwoDClosingNumberRepository::class);
        $this->app->bind(ThreeDReportRepositoryInterface::class, ThreeDReportRepository::class);
        $this->app->bind(ThreeDGameSettingRepositoryInterface::class, ThreeDGameSettingRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(ThreeDBettingWinRepositoryInterface::class, ThreeDBettingWinRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        $this->app->bind(AccountInterface::class, AccountRepository::class);
        $this->app->bind(GameInterface::class, GameRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(WalletTransactionInterface::class, WalletTransactionRepository::class);
        $this->app->bind(AgentInterface::class, AgentRepository::class);
        $this->app->bind(AgentWithdrawalTransactionInterface::class, AgentWithdrawalTransactionRepository::class);
        $this->app->bind(AdsInterface::class, AdsRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);

    }
}
