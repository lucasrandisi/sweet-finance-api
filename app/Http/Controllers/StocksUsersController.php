<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyStockRequest;
use App\Http\Resources\StockUserResource;
use App\Models\Stock;
use App\Models\User;
use App\Services\StocksUsersService;
use Illuminate\Support\Facades\Auth;

class StocksUsersController extends Controller
{
    protected StocksUsersService $stocksUsersService;

    public function __construct(
        StocksUsersService $stocksUsersService
    ) {
        $this->stocksUsersService = $stocksUsersService;
    }

    public function buy(Stock $stock, BuyStockRequest $request) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

        $result = $this->stocksUsersService->buy($currentUser, $stock, $request->amount);

        return new StockUserResource($result);
    }

    public function sell(Stock $stock, BuyStockRequest $request) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

    	$result = $this->stocksUsersService->sell($currentUser, $stock, $request->amount);

    	return new StockUserResource($result);
	}
}
