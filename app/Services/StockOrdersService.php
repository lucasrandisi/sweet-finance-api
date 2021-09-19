<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockOrder;
use App\Models\StockTransaction;
use App\Models\StockUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class StockOrdersService
{
	private TwelveDataService $twelveDataService;
	private StocksUsersService $stocksUsersService;
	private StockTransactionsService $stockTransactionsService;

	public function __construct(
		TwelveDataService $twelveDataService,
		StocksUsersService $stocksUsersService,
		StockTransactionsService $stockTransactionsService
	) {
		$this->twelveDataService = $twelveDataService;
		$this->stocksUsersService = $stocksUsersService;
		$this->stockTransactionsService = $stockTransactionsService;
	}

	/*
	 * Checks order's prices against market price
	 */
	public function checkOrders() {
		$orders = StockOrder::all();

		$symbols = $orders->groupBy('stock_symbol')->keys()->toArray();

		if (!$symbols) {
			return;
		}

		/* Implode symbols array to pass them in the querystring to get the prices */
		$parameters = ['symbol' => implode(',', $symbols)];

		$prices = $this->twelveDataService->getData('price', $parameters);

		/* Iterate over all orders */
		foreach ($orders as $order) {
			if (count($prices) == 1) {
				$stockPrice = $prices['price'];
			}
			else {
				$stockPrice = $prices[$order->stock_symbol]['price'];
			}

			if ($order->action == StockTransaction::BUY ) {
				$this->checkBuyOrder($order, $stockPrice);
			}
			else if ($order->action == StockTransaction::SELL) {
				$this->checkSellOrder($order, $stockPrice);
			}
		}
	}

	/*
	 * Check buy order stop and limit against market price
	 */
	private function checkBuyOrder($order, $stockPrice) {
		if ($order->limit < $stockPrice) {
			return;
		}

		if ($order->stop == null || $order->stop >= $stockPrice) {
			$stock = Stock::find($order->stock_symbol);

			$this->executeBuyOrder($order, $stockPrice);

			$order->delete();
		}
	}

	/*
	 * Add stocks to user and register transaction
	 */
	private function executeBuyOrder(StockOrder $order, float $stockPrice) {
		/* Update User's Stocks */
		$stockUser = StockUser::firstOrNew([
			'user_id' => $order->user_id,
			'stock_symbol' => $order->stock->symbol,
		]);
		$stockUser->amount += $order->amount;
		$stockUser->save();


		/* Save Stock Transaction */
		$this->stockTransactionsService->registerBuyTransaction(
			$order->user,
			$order->stock,
			$stockPrice,
			$order->amount
		);
	}

	/*
	 * Check sell order stop and limit against market price
	 */
	private function checkSellOrder($order, $stockPrice) {
		if ($order->limit > $stockPrice) {
			return;
		}

		if ($order->stop == null || $order->stop <= $stockPrice) {
			$stock = Stock::find($order->stock_symbol);

			$this->stocksUsersService->sell($order->user, $stock, $order->amount, $stockPrice);
		}

		$order->delete();
	}

	/*
	 * Remove stocks from user and register transaction
	 */
	private function executeSellOrder(StockOrder $order, float $stockPrice) {
		/* Add finance to User's account */
		$totalFinance = $stockPrice * $order->amount;

		$user = $order->user;

		$user->finance += $totalFinance;
		$user->save();


		/* Save Stock Transaction */
		$this->stockTransactionsService->registerSellTransaction(
			$order->user,
			$order->stock,
			$stockPrice,
			$order->amount
		);
	}
}