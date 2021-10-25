<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteStocksController;
use App\Http\Controllers\StockOrdersController;
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


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [UsersController::class, 'me']);

    Route::prefix('/stocks')->group(function() {
		Route::apiResource('/favorites', FavoriteStocksController::class)
			->only(['index', 'store', 'destroy']);

		Route::prefix('/{stock}')->group(function() {
			Route::post('buy', [StocksUsersController::class, 'buy']);
			Route::post('sell', [StocksUsersController::class, 'sell']);
		});

		Route::get('/', [StocksUsersController::class, 'index']);
	});


	Route::apiResource('orders', StockOrdersController::class)
		->only(['index', 'store', 'destroy']);

    // Twelve Data
	Route::get('/twelve-data/{path}', TwelveDataKeyController::class);
});


