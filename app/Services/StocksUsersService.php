<?php


namespace App\Services;


use App\Exceptions\UnprocessableEntityException;
use App\Models\Stock;
use App\Models\StockUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StocksUsersService
{
    protected TwelveDataService $twelveDataService;
    protected StockTransactionsService  $stockTransactionsService;

    public function __construct(
        TwelveDataService $twelveDataService,
        StockTransactionsService $stockTransactionsService
    ) {
        $this->twelveDataService = $twelveDataService;
        $this->stockTransactionsService = $stockTransactionsService;
    }

    public function buy(User $user, Stock $stock, int $amount) {
		$stockPrice = $this->getStockPrice($stock);
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
		])->firstOrFail();

		if ($stockUser->amount < $amount) {
			throw new UnprocessableEntityException('Posee menos acciones de las que desea vender', 102);
		}

		/* Discount from User's stocks */
		$stockUser->amount -= $amount;
		$stockUser->save();


		/* Add finance to User's account */
		$stockPrice = $this->getStockPrice($stock);
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

    private function getStockPrice(Stock $stock) {
        $parameters = [
            'symbol' => $stock->symbol
        ];

        $result  = $this->twelveDataService->getData('price', $parameters);

        return $result['price'];
    }
}
