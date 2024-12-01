<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AgentWithdrawalTransactionController;

Route::middleware('auth:agent_api')->group(function () {
    // Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout']);
    // Route::controller(AgentController::class)->group(function () {
    //     Route::get('customer_list_by_agent', 'customerListByAgent')->name('agent_customer');
    //     Route::get('transaction_list_by_agent', 'transactionListByAgent');
    //     Route::get('commission_amount_by_agent', 'commissionAmountOfDayByAgent');
    //     Route::get('agent_wallets', 'getAgentWallet');
    // });
    // Route::controller(NotificationController::class)->group(function () {
    //     Route::get('notifications', 'index');
    //     Route::post('read_notification', 'readNotification');
    // });

});
// Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout']);
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

