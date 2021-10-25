<?php

namespace App\Services;

use App\Models\Stock;

class StocksService
{
	private TwelveDataService $twelveDataService;

	public function __construct(TwelveDataService $twelveDataService) {
		$this->twelveDataService = $twelveDataService;
	}

	public function getOne(string $symbol) {
		return Stock::where('symbol', $symbol)->firstOr(function() use ($symbol) {
			$parameters = [
				'symbol' => $symbol,
				'country' => 'United States'
			];

			$response = $this->twelveDataService->getData('stocks', $parameters);
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

		$response = $this->twelveDataService->getData('price', $parameters);

		return $response->json('price');
	}

}