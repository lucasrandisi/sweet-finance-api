<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyStockRequest;
use App\Models\Stock;
use App\Services\StocksUsersService;

class StocksUsersController extends Controller
{
    protected StocksUsersService $stocksUsersService;

    public function __construct(
        StocksUsersService $stocksUsersService
    ) {
        $this->stocksUsersService = $stocksUsersService;
    }

    public function buy(Stock $stock, BuyStockRequest $request) {
        $result = $this->stocksUsersService->buy($stock, $request->amount);

        return response()->json($result);
    }

    public function sell(Stock $stock, BuyStockRequest $request) {
    	$result = $this->stocksUsersService->sell($stock, $request->amount);

    	return response()->json($result);
	}
}
