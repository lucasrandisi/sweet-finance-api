<?php

namespace App\Services;

use App\DataTransferObjects\StockOrderDTO;
use App\Exceptions\UnprocessableEntityException;
use App\Models\StockOrder;
use App\Models\StockTransaction;
use App\Models\StockUser;
use App\Models\User;

class StockOrdersService
{
	public function getUserOrders(User $user) {
		return StockOrder::where('user_id', $user->id);
	}


	/* Stores new order */
	public function store(User $user, StockOrderDTO $orderDTO) {
		if ($orderDTO->action == StockTransaction::BUY) {
			$this->discountUserFinance($user, $orderDTO);
		}
		else if ($orderDTO->action == StockTransaction::SELL) {
			$this->discountUserStocks($user, $orderDTO);
		}

		/* Create Order */
		return StockOrder::create([
			'user_id' => $user->id,
			'stock_symbol' => $orderDTO->stock_symbol,
			'action' => $orderDTO->action,
			'amount' => $orderDTO->amount,
			'limit' => $orderDTO->limit,
			'stop' => $orderDTO->stop,
			'state' => $orderDTO->stop ? StockOrder::INACTIVE_STATE : StockOrder::ACTIVE_STATE
		]);
	}

	/* Discounts user's finance when crating a buy order */
	private function discountUserFinance(User $user, StockOrderDTO $orderDTO) {
		$totalPrice = $orderDTO->amount * $orderDTO->limit;

		if ($user->finance < $totalPrice) {
			throw new UnprocessableEntityException('Finance insuficiente para realizar la operación', 100);
		}

		/* Discount finance from user */
		$user->finance -= $totalPrice;
		$user->save();
	}

	/* Discounts user's stocks when crating a sell order */
	private function discountUserStocks(User $user, StockOrderDTO $orderDTO) {
		$stockUser = StockUser::where([
			'stock_symbol' => $orderDTO->stock_symbol,
			'user_id' => $user->id
		])->first();

		if (!$stockUser || $stockUser->amount < $orderDTO->amount) {
			throw new UnprocessableEntityException('Posee menos acciones de las que desea vender', 102);
		}

		/* Discount from User's stocks */
		$stockUser->amount -= $orderDTO->amount;
		$stockUser->save();
	}

	public function deleteOrder(int $id) {
		$stockOrder = StockOrder::find($id);

		/* Return user's finance */
		if ($stockOrder->action === StockTransaction::BUY) {
			$user = $stockOrder->user;
			$user->finance += $stockOrder->amount * $stockOrder->limit;
			$user->save();
		}
		/* Return user's stocks */
		else if ($stockOrder->action === StockTransaction::SELL) {
			$stockUser = StockUser::where([
				'stock_symbol' => $stockOrder->stock_symbol,
				'user_id' => $stockOrder->user_id
			])->first();

			$stockUser->amount += $stockOrder->amount;
			$stockUser->save();
		}

		return StockOrder::destroy($id);
	}
}