<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlphaVantageController;
use App\Http\Controllers\StocksUsersController;
use App\Http\Controllers\TwelveDataKeyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Stocks
    Route::prefix('/stocks/{stock}')->group(function() {
        Route::post('buy', [StocksUsersController::class, 'buy']);
		Route::post('sell', [StocksUsersController::class, 'sell']);
	});


    Route::prefix('/twelve-data')->group((function() {
		Route::get('stocks', [TwelveDataKeyController::class, 'stocks']);
		Route::get('price', [TwelveDataKeyController::class, 'price']);
	}));

    // Alpha Vantage
    Route::get('/alpha-vantage', AlphaVantageController::class);
});


