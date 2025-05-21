<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LiveData\TwoDAPIController;
use App\Http\Controllers\API\LiveData\ThreeDAPIController;
use App\Http\Controllers\API\LiveData\DreamNumberAPIController;

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

Route::post('/2d/import', [TwoDAPIController::class, 'importTwoDResults']);
Route::post('/2d/fetch_historical_results', [TwoDAPIController::class, 'fetchHitoricalResults']);
Route::get('/2d/live', [TwoDAPIController::class, 'getLiveResult']);
Route::get('/2d/results', [TwoDAPIController::class, 'getDailyResults']);
Route::get('/2d/results/monthly', [TwoDAPIController::class, 'getMonthlyResults']);
Route::get('/2d/analysis', [TwoDAPIController::class, 'getAnalysis']);
Route::get('/2d/lucky_numbers', [TwoDAPIController::class, 'getLuckyNumbers']);
Route::get('/2d/modern_internet_results', [TwoDAPIController::class, 'fetchModernInternet']);

Route::post('/3d/import', [ThreeDAPIController::class, 'importThreeDResults']);
Route::get('/3d/results', [ThreeDAPIController::class, 'getResults']);
Route::post('/3d/results', [ThreeDAPIController::class, 'saveThreeDResult']);

Route::get('/3d/results/yearly', [ThreeDAPIController::class, 'getYearlyResults']);
Route::get('/3d/results/calendar', [ThreeDAPIController::class, 'getCalendarResults']);
Route::get('/3d/analysis', [ThreeDAPIController::class, 'getAnalysis']);

Route::post('/dream_numbers/import', [DreamNumberAPIController::class, 'importDreamNumberSheet']);
Route::get('/dream_numbers', [DreamNumberAPIController::class, 'getDreamNumbers']);
