<?php


namespace App\Services;


use App\Exceptions\UnprocessableEntityException;
use App\Models\Stock;
use App\Models\StockUser;
use App\Models\User;

class StocksUsersService
{
    protected StockTransactionsService  $stockTransactionsService;
	protected StocksService $stocksService;

    public function __construct(
        StockTransactionsService $stockTransactionsService,
		StocksService $stockService
    ) {
        $this->stockTransactionsService = $stockTransactionsService;
		$this->stocksService = $stockService;
    }

    public function buy(User $user, Stock $stock, int $amount) {
		$stockPrice = $this->stocksService->getPrice($stock->symbol);
        $totalPrice = $stockPrice * $amount;


        if ($user->finance < $totalPrice) {
            throw new UnprocessableEntityException('Finance insuficiente para realizar la operación', 100);
        }

		/* Discount finance from user */
		$user->finance -= $totalPrice;
		$user->save();


		/* Update User's Stocks */
		$stockUser = StockUser::firstOrNew([
            'user_id' => $user->id,
            'stock_symbol' => $stock->symbol,
        ]);
		$stockUser->amount += $amount;
		$stockUser->save();


		/* Save Stock Transaction */
        $this->stockTransactionsService->registerBuyTransaction(
			$user,
            $stock,
            $stockPrice,
            $amount
        );

        return $stockUser;
    }

	public function sell(User $user, Stock $stock, int $amount) {
		$stockUser = StockUser::where([
			'stock_symbol' => $stock->symbol,
			'user_id' => $user->id
		])->first();

		if (!$stockUser || $stockUser->amount < $amount) {
			throw new UnprocessableEntityException('Posee menos acciones de las que desea vender', 102);
		}

		/* Discount from User's stocks */
		$stockUser->amount -= $amount;

		if ($stockUser->amount == 0) {
			$stockUser->delete();
		}
		else {
			$stockUser->save();
		}



		/* Add finance to User's account */
		$stockPrice = $this->stocksService->getPrice($stock->symbol);
		$totalPrice = $stockPrice * $amount;


		$user->finance += $totalPrice;
		$user->save();


		/* Save Stock Transaction */
		$this->stockTransactionsService->registerSellTransaction(
			$user,
			$stock,
			$stockPrice,
			$amount
		);

		return $stockUser;
	}

	public function getUserStocks(User $user) {
		return StockUser::where([
			'user_id' => $user->id
		])->get();
	}
}
