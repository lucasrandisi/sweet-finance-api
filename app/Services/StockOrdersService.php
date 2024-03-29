<?php

namespace App\Services;

use App\DataTransferObjects\StockOrderDTO;
use App\Exceptions\UnprocessableEntityException;
use App\Models\StockOrder;
use App\Models\StockTransaction;
use App\Models\StockUser;
use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

class StockOrdersService
{
	private StocksService $stocksService;

	public function __construct(StocksService $stocksService) {
		$this->stocksService = $stocksService;
	}

	public function getUserOrders(User $user) {
		return StockOrder::where('user_id', $user->id)->get();
	}


	/* Stores new order */
	public function store(User $user, StockOrderDTO $orderDTO) {
		if ($orderDTO->action == StockTransaction::BUY) {
			$this->discountUserFinance($user, $orderDTO);
		}
		else if ($orderDTO->action == StockTransaction::SELL) {
			$this->discountUserStocks($user, $orderDTO);
		}

		$stockPrice = $this->stocksService->getPrice($orderDTO->stock_symbol);

		/* Create Order */
		return StockOrder::create([
			'user_id' => $user->id,
			'stock_symbol' => $orderDTO->stock_symbol,
			'action' => $orderDTO->action,
			'amount' => $orderDTO->amount,
			'limit' => $orderDTO->limit,
			'stop' => $orderDTO->stop,
			'state' => $orderDTO->stop ? StockOrder::INACTIVE_STATE : StockOrder::ACTIVE_STATE,
			'price_at_create_time' => $stockPrice
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

		if ($stockUser->amount == 0) {
			$stockUser->delete();
		}
		else {
			$stockUser->save();
		}
	}

	public function deleteOrder(int $orderId, int $userId) {
		$stockOrder = StockOrder::where([
			'id' => $orderId,
			'user_id' => $userId
		])->first();

		if (!$stockOrder) {
			return false;
		}

		if ($stockOrder->user_id !== $userId) {
			throw new UnauthorizedException();
		}

		if ($stockOrder->state === StockOrder::COMPLETE_STATE) {
			throw new UnprocessableEntityException('No se puede eliminar una orden completada', '200');
		}

		/* Return user's finance */
		if ($stockOrder->action === StockTransaction::BUY) {
			$user = $stockOrder->user;
			$user->finance += $stockOrder->amount * $stockOrder->limit;
			$user->save();
		}

		/* Return user's stocks */
		else if ($stockOrder->action === StockTransaction::SELL) {
			$stockUser = StockUser::firstOrCreate([
				'stock_symbol' => $stockOrder->stock_symbol,
				'user_id' => $stockOrder->user_id
			]);

			$stockUser->amount += $stockOrder->amount;
			$stockUser->save();
		}

		return $stockOrder->delete();
	}
}