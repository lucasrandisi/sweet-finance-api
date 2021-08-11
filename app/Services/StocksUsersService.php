<?php


namespace App\Services;


use App\Exceptions\UnprocessableEntityException;
use App\Models\Stock;
use App\Models\StockUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StocksUsersService
{
    protected AlphaVantageService $alphaVantageService;
    protected StockTransactionsService  $stockTransactionsService;

    public function __construct(
        AlphaVantageService $alphaVantageService,
        StockTransactionsService $stockTransactionsService
    ) {
        $this->alphaVantageService = $alphaVantageService;
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

        /* Save Stock Transaction */
        $stockTransaction = $this->stockTransactionsService->registerBuyTransaction(
            $currentUser,
            $stock,
            $stockPrice,
            $amount
        );

        return [$stockUser, $stockTransaction];
    }


    private function getStockPrice(Stock $stock) {
        $parameters = [
            'function' => 'GLOBAL_QUOTE',
            'symbol' => $stock->symbol
        ];

        return $this->alphaVantageService->query($parameters)['Global Quote']['05. price'];
    }
}
