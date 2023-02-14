<?php

namespace App\Services;

use App\Clients\TwelveDataClient;
use App\Models\Stock;

class StocksService
{
	private TwelveDataClient $twelveDataClient;

	public function __construct(TwelveDataClient $twelveDataClient) {
		$this->twelveDataClient = $twelveDataClient;
	}

    public function getAll($limit, $filters) {
        $query = Stock::query();

        foreach ($filters as $key => $value) {
            if ($key === 'search') {
                $query->where('symbol', 'LIKE', "%$value%")
                    ->orWhere('name', 'LIKE', "%$value%");
            }
        }

        $query->limit($limit);

        return $query->get();
    }

	public function getOne(string $symbol) {
		return Stock::where('symbol', $symbol)->firstOr(function() use ($symbol) {
			$parameters = [
				'symbol' => $symbol,
				'country' => 'United States'
			];

			$response = $this->twelveDataClient->getData('stocks', $parameters);
			if (!$response->json('data')) {
				return null;
			}

			$retrievedStock = $response->json('data')[0];


			$stock = Stock::create([
				'symbol' => $retrievedStock['symbol'],
				'name' => $retrievedStock['name']
			]);

			return $stock;
		});
	}

	function getPrice(string $stock_symbol) {
		$parameters = [
			'symbol' => $stock_symbol
		];

		$response = $this->twelveDataClient->getData('price', $parameters);

		return $response->json('price');
	}

}
