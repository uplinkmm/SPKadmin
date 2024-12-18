<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SlotTransactionController;

Route::controller(SlotTransactionController::class)->group(function () {
    Route::get('slot_transaction','index');
});