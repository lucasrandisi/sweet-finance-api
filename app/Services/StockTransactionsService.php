<?php


namespace App\Services;


use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\User;

class StockTransactionsService
{
    public function registerBuyTransaction(User $user, Stock $stock, float $price, int $amount) {
        return StockTransaction::create([
            'user_id' => $user->id,
            'stock_symbol' => $stock->symbol,
            'action' => 'BUY',
            'price' => $price,
            'amount' => $amount
        ]);
    }

	public function registerSellTransaction(User $user, Stock $stock, float $price, int $amount) {
		return StockTransaction::create([
			'user_id' => $user->id,
			'stock_symbol' => $stock->symbol,
			'action' => 'SELL',
			'price' => $price,
			'amount' => $amount
		]);
	}
}