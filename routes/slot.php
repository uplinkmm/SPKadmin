<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SlotGameController;
use App\Http\Controllers\API\SlotTransactionController;

Route::controller(SlotTransactionController::class)->group(function () {
    Route::get('slot_transaction','index');
});

Route::controller(SlotGameController::class)->group(function () {
    Route::get('game_type_list','getGameTypeList');
    Route::get('product_list_by_game_type/{gameTypeId}','productListByGameType');
});