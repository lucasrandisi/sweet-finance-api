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

    public function buy(Stock $stock, int $amount) {
        $stockPrice = $this->getStockPrice($stock);

        $totalPrice = $stockPrice * $amount;

        /*  @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->finance < $totalPrice) {
            throw new UnprocessableEntityException('Finance insuficiente para realizar la operación', 100);
        }

        /* Update User's Stocks */
        $stockUser = StockUser::firstOrNew([
            'user_id' => $currentUser->id,
            'stock_symbol' => $stock->symbol,
        ]);
        $stockUser->amount += $amount;
        $stockUser->save();


        /* Discount from User's Finance */
        $currentUser->finance -= $totalPrice;
        $currentUser->save();


        /* Save Stock Transaction */
        $this->stockTransactionsService->registerBuyTransaction(
            $currentUser,
            $stock,
            $stockPrice,
            $amount
        );

        return $stockUser;
    }

	public function sell(Stock $stock, int $amount) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$stockUser = StockUser::where([
			'stock_symbol' => $stock->symbol,
			'user_id' => $currentUser->id
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

		$currentUser->finance += $totalPrice;
		$currentUser->save();


		/* Save Stock Transaction */
		$this->stockTransactionsService->registerSellTransaction(
			$currentUser,
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
