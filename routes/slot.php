<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SlotGameController;
use App\Http\Controllers\API\SlotTransactionController;

// Route::middleware('auth:api')->group(function () {

    Route::controller(SlotTransactionController::class)->group(function () {
        Route::get('slot_transaction', 'index');
        Route::get('slot_provider_report', 'slotProviderReport');
        Route::get('slot_user_report', 'slotUserReport');
        Route::get('slot_user_list', 'slotUserList');
    });

    Route::controller(SlotGameController::class)->group(function () {
        Route::get('game_type_list', 'getGameTypeList');
        Route::get('product_list_by_game_type/{gameTypeId}', 'productListByGameType');
    });
// });