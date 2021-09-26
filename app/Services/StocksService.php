<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
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
			$retrievedStock = $response->json('data')[0];


			if (!$retrievedStock) {
				return null;
			}

			$stock = Stock::create([
				'symbol' => $retrievedStock['symbol'],
				'name' => $retrievedStock['name']
			]);

			return $stock;
		});

	}

}