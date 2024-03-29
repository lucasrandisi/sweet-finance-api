<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteStocksController;
use App\Http\Controllers\FmpController;
use App\Http\Controllers\MarketauxController;
use App\Http\Controllers\StockOrdersController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\StocksUsersController;
use App\Http\Controllers\TwelveDataKeyController;
use App\Http\Controllers\UsersController;
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


// Twelve Data
Route::get('/twelve-data/{path}', TwelveDataKeyController::class);

// Marketaux
Route::get('/marketaux', MarketauxController::class);

// Fmp
Route::get('/fmp', FmpController::class);


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
	Route::post('/change-password', [AuthController::class, 'changePassword']);

	// Me
    Route::get('/me', [UsersController::class, 'me']);
	Route::patch('/me', [UsersController::class, 'updateMe']);
    Route::post('/finance', [UsersController::class, 'addFinance']);

	// Stocks
    Route::prefix('/stocks')->group(function() {
		Route::apiResource('/favorites', FavoriteStocksController::class)
			->only(['index', 'store', 'destroy']);

		Route::get('/', [StocksController::class, 'index']);

        Route::prefix('/{stock}')->group(function() {
			Route::post('buy', [StocksUsersController::class, 'buy']);
			Route::post('sell', [StocksUsersController::class, 'sell']);
		});
	});

    Route::get('/user-stocks', [StocksUsersController::class, 'index']);


	// Orders
	Route::apiResource('orders', StockOrdersController::class)
		->only(['index', 'store', 'destroy']);
});


