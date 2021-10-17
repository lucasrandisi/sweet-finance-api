<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockOrder;
use App\Models\StockTransaction;
use App\Models\StockUser;

class ExecuteOrdersService
{
	private TwelveDataService $twelveDataService;
	private StockTransactionsService $stockTransactionsService;

	public function __construct(
		TwelveDataService $twelveDataService,
		StockTransactionsService $stockTransactionsService
	) {
		$this->twelveDataService = $twelveDataService;
		$this->stockTransactionsService = $stockTransactionsService;
	}

	/* Checks order's prices against market price  */
	public function checkOrders() {
		$orders = StockOrder::where('state', '!=', StockOrder::COMPLETE_STATE)->get();

		$symbols = $orders->groupBy('stock_symbol')->keys()->toArray();

		if (!$symbols) {
			return;
		}

		/* Implode symbols array to pass them in the querystring to get the prices */
		$parameters = ['symbol' => implode(',', $symbols)];

		$prices = $this->twelveDataService->getData('price', $parameters)->json();


		/* Iterate over all orders */
		foreach ($orders as $order) {
			if (count($prices) == 1) {
				$stockPrice = $prices['price'];
			} else {
				$stockPrice = $prices[$order->stock_symbol]['price'];
			}

			if ($order->action == StockTransaction::BUY) {
				$this->checkBuyOrder($order, $stockPrice);
			} else if ($order->action == StockTransaction::SELL) {
				$this->checkSellOrder($order, $stockPrice);
			}
		}
	}

	/* Check buy order stop and limit against market price */
	private function checkBuyOrder(StockOrder $order, float $stockPrice) {
		/* Limit Order */
		if ($order->stop == null && $stockPrice <= $order->limit) {
			$this->executeBuyOrder($order, $stockPrice);
		}
		/* Stop less than stock price at create time */
		else if ($order->stop <= $order->price_at_create_time) {
			if ($order->state == StockOrder::INACTIVE_STATE && $stockPrice <= $order->stop) {
				$order->state = StockOrder::ACTIVE_STATE;
				$order->save();
			}

			if ($order->state == StockOrder::ACTIVE_STATE && $stockPrice <= $order->limit) {
				$this->executeBuyOrder($order, $stockPrice);
			}
		}
		/* Stop greater than stock price at create time */
		else if ($order->stop > $order->price_at_create_time) {
			if ($order->state == StockOrder::INACTIVE_STATE && $stockPrice >= $order->stop) {
				$order->state = StockOrder::ACTIVE_STATE;
				$order->save();
			}

			if ($order->state == StockOrder::ACTIVE_STATE && $stockPrice <= $order->limit) {
				$this->executeBuyOrder($order, $stockPrice);
			}
		}
	}



	/* Add stocks to user and register transaction */
	private function executeBuyOrder(StockOrder $order, float $stockPrice) {
		/* Update User's Stocks */
		$stockUser = StockUser::firstOrNew([
			'user_id' => $order->user_id,
			'stock_symbol' => $order->stock_symbol,
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

		/* Return user's finance surplus */
		$user = $stockUser->user;

		$user->finance += $order->litmit - $stockPrice;
		$user->save();


		/* Mark order as complete */
		$order->state = StockOrder::COMPLETE_STATE;
		$order->save();
	}

	/* Check sell order stop and limit against market price */
	private function checkSellOrder($order, $stockPrice) {
		/* Limit Order */
		if ($order->stop == null && $stockPrice >= $order->limit) {
			$this->executeSellOrder($order, $stockPrice);
		}
		/* Stop less than stock price at create time */
		else if ($order->stop <= $order->price_at_create_time) {
			if ($order->state == StockOrder::INACTIVE_STATE && $stockPrice <= $order->stop) {
				$order->state = StockOrder::ACTIVE_STATE;
				$order->save();
			}

			if ($order->state == StockOrder::ACTIVE_STATE && $stockPrice >= $order->limit) {
				$this->executeSellOrder($order, $stockPrice);
			}
		}
		/* Stop greater than stock price at create time */
		else if ($order->stop > $order->price_at_create_time) {
			if ($order->state == StockOrder::INACTIVE_STATE && $stockPrice >= $order->stop) {
				$order->state = StockOrder::ACTIVE_STATE;
				$order->save();
			}

			if ($order->state == StockOrder::ACTIVE_STATE && $stockPrice >= $order->limit) {
				$this->executeSellOrder($order, $stockPrice);
			}
		}
	}

	/* Remove stocks from user and register transaction */
	private function executeSellOrder(StockOrder $order, float $stockPrice) {
		/* Update User's finance */
		$user = $order->user;
		$user->finance += $order->amount * $stockPrice;
		$user->save();

		/* Save Stock Transaction */
		$this->stockTransactionsService->registerSellTransaction(
			$order->user,
			$order->stock,
			$stockPrice,
			$order->amount
		);

		/* Mark order as complete */
		$order->state = StockOrder::COMPLETE_STATE;
		$order->save();
	}
}