<?php

namespace App\Services;

use App\DataTransferObjects\StockOrderDTO;
use App\Exceptions\UnprocessableEntityException;
use App\Models\Stock;
use App\Models\StockOrder;
use App\Models\StockTransaction;
use App\Models\StockUser;
use App\Models\User;

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

	/* Stores new order */
	public function store(Stock $stock, User $user, StockOrderDTO $orderDTO) {
		if ($orderDTO->action == StockTransaction::BUY) {
			$this->discountUserFinance($user, $orderDTO);
		}
		else if ($orderDTO->action == StockTransaction::SELL) {
			$this->discountUserStocks($stock, $user, $orderDTO);
		}

		/* Create Order */
		return StockOrder::create([
			'user_id' => $user->id,
			'stock_symbol' => $stock->symbol,
			'action' => $orderDTO->action,
			'amount' => $orderDTO->amount,
			'limit' => $orderDTO->limit,
			'stop' => $orderDTO->stop
		]);
	}

	/* Discounts user's finance when crating buy order */
	private function discountUserFinance(User $user, StockOrderDTO $orderDTO) {
		$totalPrice = $orderDTO->amount * $orderDTO->limit;

		if ($user->finance < $totalPrice) {
			throw new UnprocessableEntityException('Finance insuficiente para realizar la operación', 100);
		}

		/* Discount finance from user */
		$user->finance -= $totalPrice;
		$user->save();
	}

	/* Discounts user's stocks when crating sell order */
	private function discountUserStocks(Stock $stock, User $user, StockOrderDTO $orderDTO) {
		$stockUser = StockUser::where([
			'stock_symbol' => $stock->symbol,
			'user_id' => $user->id
		])->firstOrFail();

		if ($stockUser->amount < $orderDTO->amount) {
			throw new UnprocessableEntityException('Posee menos acciones de las que desea vender', 102);
		}

		/* Discount from User's stocks */
		$stockUser->amount -= $orderDTO->amount;
		$stockUser->save();
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