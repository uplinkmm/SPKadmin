<?php

use App\Http\Controllers\WEB\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return redirect()->route('twod_reports.bettings_overview.index');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/agent/login', 'auth.login')->name('agent_login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::view('/topup_transactions', 'topup_transactions.index')->name('topup_transactions.index');
// Route::middleware('auth.web')->group(function () {
Route::middleware(['auth.web'])->group(function () {

    Route::middleware(['user-permission:transaction'])->group(function () {
        Route::view('/topup_transactions', 'topup_transactions.index')->name('topup_transactions.index');
        Route::view('/cash_withdrawal_transactions', 'cash_withdrawal_transactions.index')->name('cash_withdrawal_transactions.index');
    });
    Route::middleware(['user-permission:2d'])->group(function () {
        Route::view('/2d/reports/bettings_overview', 'twod_reports.bettings_overview.index')->name('twod_reports.bettings_overview.index');
        Route::view('/2d/reports/betting_amounts', 'twod_reports.betting_amounts.index')->name('twod_reports.betting_amounts.index');
        Route::view('/2d/reports/customer_bets', 'twod_reports.customer_bets.index')->name('twod_reports.customer_bets.index');
        Route::view('/2d/reports/bet_list', 'twod_reports.bet_list.index')->name('twod_reports.bet_list.index');
        Route::view('/2d/bingo_customers', 'twod_reports.winner_list.index')->name('twod_reports.winner_list.index');

        Route::view('/2d/winning_numbers', 'twod_reports.winning_number.index')->name('twod_reports.winning_numbers.index');
        Route::view('/2d/closing_numbers', 'twod_reports.closing.index')->name('twod_reports.closing_numbers.index');
    });

    Route::middleware(['user-permission:setting'])->group(function () {
        Route::view('/users', 'user.index')->name('users.index');
        Route::view('/limit_user', 'user.limit_user')->name('users.limit_user');
        Route::view('/withdrawal_history', 'history.withdrawal')->name('history.withdrawal');
        Route::view('/deposit_history', 'history.deposit')->name('history.deposit');
        Route::view('/balance', 'balance_transaction.index')->name('balance_transactions.index');
        Route::view('/setting', 'setting.index')->name('settings');
        Route::view('/payment_providers', 'payment_provider.index')->name('payment_providers.index');
        Route::view('/game_lists', 'setting.games')->name('games');
        Route::view('/ads_lists', 'ads.ads')->name('ads_lists');
        Route::view('/admin_users', 'user.admin_users')->name('admin_users');
        Route::view('/agent_lists', 'agents.agent_lists')->name('agent_lists');
        // Route::view('/agents_users', 'agents.agents_users')->name('agents_users');
        Route::view('/ads_lists', 'ads.ads')->name('ads_lists');
    });

    Route::middleware(['user-permission:3d'])->group(function () {
        Route::view('/3d/reports/bettings_overview', 'threed_reports.betting_overview.index')->name('threed_reports.betting_overview.index');
        Route::view('/3d/reports/betting_amount', 'threed_reports.betting_amount.index')->name('threed_reports.betting_amounts.index');
        Route::view('/3d/reports/customer_bets', 'threed_reports.customer_bets.index')->name('threed_reports.customer_bets.index');
        Route::view('/3d/reports/bet_list', 'threed_reports.bet_list.index')->name('threed_reports.bet_list.index');
        Route::view('/3d/bingo_customers', 'threed_reports.winner_list.index')->name('threed_reports.winner_list.index');

        // <<<<<<< HEAD
        Route::view('/3d/winning_numbers', 'threed_reports.winning_number.index')->name('threed_reports.winning_numbers.index');
        Route::view('/3d/game_settings', 'threed_reports.game_setting.index')->name('threed_reports.game_setting.index');
        Route::view('/threeclosing', 'threed_reports.closing.index');
    });
    Route::view('/slot/transcations', 'slot.transcation')->name("slot_transcation");
    Route::view('/slot/provider_report', 'slot.provider_report')->name("provider_report");
    Route::view('/slot/user_report', 'slot.user_report')->name("user_report");
    Route::view('/slot/user_lists', 'slot.slot_user_lists')->name("slot_user_lists");
    Route::view('/slot/game_lists', 'slot.slot_game_lists')->name("slot_game_lists");
    Route::view('/contact_numbers', 'contact.index')->name("contacts");
    Route::view('/feedbacks', 'Feedbacks.feedbacks')->name("feedbacks");

    Route::view('/draw/game_lists', 'draw.draw_game_lists')->name("draw_game_lists");
    Route::view('/draw/game_create', 'draw.draw_game_create')->name("draw_game_create");

});

Route::middleware(['auth.agent'])->group(function () {
    Route::view('/agents_users', 'agents.agents_users')->name('agents_users');
    Route::get('/game_transitions/{game_type}', function ($game_type) {
        return view('agents.two_d_three_d_transitions', ['game_type' => $game_type]);
    });
    Route::view('/agents_commission', 'agents.agents_commission')->name('agents_commission');
    Route::view('/agent_wallets', 'agents.agent_wallets')->name('agent_wallets');
    Route::view('/agent_transcations_status', 'agents.agent_transcations_status')->name('agent_transcations_status');
    // Other routes accessible to both web users and agents
});
//test

// Route::view('/reports', 'twod_reports.reports.index');
// Route::view('/betlist', 'twod_reports.bet_list.index');
// Route::view('/winner', 'twod_reports.winner_list.index');
// Route::view('/winning_number', 'twod_reports.winning_number.index');
// Route::view('/closing', 'twod_reports.closing.index');
// Route::view('/3d_overview', 'threed_reports.betting_overview.index');
// Route::view('/3d_winning_number', 'threed_reports.winning_number.index');
// Route::view('/3d_betlist', 'threed_reports.bet_list.index');
// Route::view('/3d_bettingamount', 'threed_reports.betting_amount.index');
// Route::view('/3d_customerbet', 'threed_reports.customer_bets.index');
// Route::view('/3d_winnerlist', 'threed_reports.winner_list.index');
// Route::view('/game', 'threed_reports.game_setting.index');
Route::view('/deposits', 'deposits.index');
Route::view('/threeclosing', 'threed_reports.closing.index');
Route::view('/test', 'test.test');
Route::view('/TermsAndConditions', 'TermsAndConditions.TermsAndConditions')->name("TermsAndConditions");
