<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AdsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\TwoDBingoAPIController;
use App\Http\Controllers\API\TwoDReportAPIController;
use App\Http\Controllers\API\ThreeDReportAPIController;
use App\Http\Controllers\API\TermAndConditionController;
use App\Http\Controllers\API\TwoDBettingWinAPIController;
use App\Http\Controllers\API\WalletTransactionController;
use App\Http\Controllers\API\ThreeDBettingWinAPIController;
use App\Http\Controllers\API\TopupTransactionAPIController;
use App\Http\Controllers\API\ThreeDGameSettingAPIController;
use App\Http\Controllers\API\TwoDClosingNumberAPIController;
use App\Http\Controllers\API\AgentWithdrawalTransactionController;
use App\Http\Controllers\API\CashWithdrawlTransactionAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout']);
    // Route::get('/topup_transactions', [TopupTransactionAPIController::class, 'getTransactionList']);
    // Route::post('/topup_transactions/{transaction}/confirm_reject', [TopupTransactionAPIController::class, 'confirmOrRejectTransaction']);
    // Route::get('/cash_withdrawl_transactions', [CashWithdrawlTransactionAPIController::class, 'getTransactionList']);
    // Route::post('/cash_withdrawl_transactions/{transaction}/confirm_reject', [CashWithdrawlTransactionAPIController::class, 'confirmOrRejectTransaction']);
    Route::controller(TopupTransactionAPIController::class)->group(function () {
        Route::post('/topup', 'store');
        Route::get('/topup_transactions', 'getTransactionList');
        Route::post('/topup_transactions/{transaction}/confirm_reject', 'confirmOrRejectTransaction');
        Route::get('/topup_transaction_history', 'getTopupTransactionHistory');
    });
    Route::controller(CashWithdrawlTransactionAPIController::class)->group(function () {
        Route::post('/withdrawal', 'store');
        Route::get('/cash_withdrawl_transactions', 'getTransactionList');
        Route::post('/cash_withdrawl_transactions/{transaction}/confirm_reject', 'confirmOrRejectTransaction');
        Route::get('/withdrawal_transaction_history', 'getWithdrawalTransactionHistory');
    });


    Route::get('/2d/report/summary', [TwoDReportAPIController::class, 'getSummaryReport']);
    Route::get('/2d/report/detail', [TwoDReportAPIController::class, 'getDetailReport']);
    Route::get('/2d/report/customer_bets', [TwoDReportAPIController::class, 'getCustomerWithBetAmounts']);
    Route::get('/2d/report/bet_list', [TwoDReportAPIController::class, 'getCustomerBetList']);
    Route::get('/2d/bingo/customers', [TwoDBingoAPIController::class, 'getBingoCustomers']);

    Route::get('/2d/betting_wins', [TwoDBettingWinAPIController::class, 'list']);
    Route::post('/2d/betting_wins', [TwoDBettingWinAPIController::class, 'create']);
    Route::post('/2d/betting_wins/{bettingWin}', [TwoDBettingWinAPIController::class, 'update']);
    Route::post('/2d/betting_wins/{bettingWin}/approve', [TwoDBettingWinAPIController::class, 'approve']);

    // Route::get('/2d/closing_numbers', [TwoDClosingNumberAPIController::class, 'list']);
    Route::post('/2d/closing_numbers/set_inactive', [TwoDClosingNumberAPIController::class, 'setInactive']);
    // Route::post('/2d/closing_numbers', [TwoDClosingNumberAPIController::class, 'create']);
    Route::controller(TwoDClosingNumberAPIController::class)->group(function () {
        Route::get('closing_number_list', 'list');
        Route::post('create_closing_number', 'create');
        Route::post('closing_number/set_inactive', [TwoDClosingNumberAPIController::class, 'setInactive']);
    });

    Route::get('/3d/game_settings', [ThreeDGameSettingAPIController::class, 'list']);
    Route::post('/3d/game_settings', [ThreeDGameSettingAPIController::class, 'create']);

    Route::get('/3d/report/summary', [ThreeDReportAPIController::class, 'getSummaryReport']);
    Route::get('/3d/report/detail', [ThreeDReportAPIController::class, 'getDetailReport']);
    Route::get('/3d/report/customer_bets', [ThreeDReportAPIController::class, 'getCustomerWithBetAmounts']);
    Route::get('/3d/report/bet_list', [ThreeDReportAPIController::class, 'getCustomerBetList']);
    Route::get('/3d/bingo/customers', [ThreeDReportAPIController::class, 'getBingoCustomers']);

    Route::get('/3d/betting_wins', [ThreeDBettingWinAPIController::class, 'list']);
    Route::post('/3d/betting_wins', [ThreeDBettingWinAPIController::class, 'create']);
    Route::post('/3d/betting_wins/{bettingWin}', [ThreeDBettingWinAPIController::class, 'update']);
    Route::post('/3d/betting_wins/{bettingWin}/approve', [ThreeDBettingWinAPIController::class, 'approve']);

    #dashboard report
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard_crn', 'getDashboardCRN');
        Route::get('fianancial_report', 'getFinancialReport');
        Route::post('update_dashboard_data', 'updateDashboardData');
        Route::get('customers', 'getCustomerList');
        Route::get('get_customer_limitation_list', 'getCustomerLimitationList');
    });
    #cusotmer
    Route::controller(CustomerController::class)->group(function () {
        Route::get('customers', 'getCustomerList');
        Route::post('customers', 'store');
        Route::get('get_customer_limitation_list', 'getCustomerLimitationList');
        Route::post('update_customer_bet_limit', 'updateCustomerBetLimit');
    });
    #game
    Route::resource('games', GameController::class)->only(['index', 'show', 'store']);
    Route::controller(GameController::class)->group(function () {
        Route::get('/game_list', [GameController::class, 'game_list']);
        Route::post('game_settings', 'storeGameSetting');
        Route::post('games/toggle_is_active', 'toggleIsActive');
        Route::get('3d/game_setting', 'get3DGameSetting');
    });

    #account
    Route::resource('accounts', AccountController::class)->only(['index', 'show', 'store']);
    Route::controller(AccountController::class)->group(function () {
        Route::post('accounts/toggle_is_active', 'toggleIsActive');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index');
        Route::post('read_notification', 'readNotification');
    });
    Route::resource('agents', AgentController::class)->only(['index', 'show', 'store']);
    Route::controller(AgentController::class)->group(function () {
        Route::post('agents/toggle_is_active', 'toggleIsActive');
    });

    Route::controller(WalletTransactionController::class)->group(function () {
        Route::get('get_balance_transaction', 'getBalanceTransaction');

    });


    #ads
    Route::resource('ads', AdsController::class)->only(['index', 'show', 'store']);

    #user
    Route::resource('users', UserController::class)->only(['index', 'show', 'store']);
    Route::controller(UserController::class)->group(function () {
        Route::get('permissions', 'getPermission');
    });
    // Route::get('/2d/game_settings', [CommonController::class, 'twoDGameSettings']);
    // Route::get('/3d/game_settings', [CommonController::class, 'threeDGameSettings']);

    Route::controller(AgentController::class)->group(function () {
        Route::get('customer_list_by_agent', 'customerListByAgent')->name('agent_customer');
        Route::get('transaction_list_by_agent', 'transactionListByAgent');
        Route::get('commission_amount_by_agent', 'commissionAmountOfDayByAgent');
        Route::get('agent_wallets', 'getAgentWallet');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index');
        Route::post('read_notification', 'readNotification');
    });


    Route::controller(AgentWithdrawalTransactionController::class)->group(function () {
        Route::get('agent_withdrawal_transactions', 'index');
        Route::post('create_agent_withdrawal_transaction', 'store');
        Route::post('update_agent_wallet_transaction_status', 'updateAgentWalletTransactionStatus');
    });
    Route::resource('term_and_conditions', TermAndConditionController::class)->only(['index', 'show', 'store']);
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'store']);
    Route::resource('feedbacks', FeedbackController::class)->only(['index','destroy']);

});
// Route::get('customer_list_by_agent','customerListByAgent')->name('admin_customer');

Route::get('/2d/game_settings', [CommonController::class, 'twoDGameSettings']);
Route::get('/3d/game_settings', [CommonController::class, 'threeDGameSettings']);

Route::get('send_noti', [TestController::class, 'testNoti']);

// Include the admin routes
require_once __DIR__ . '/versiononeapis.php';
