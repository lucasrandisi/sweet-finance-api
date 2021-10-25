<?php

namespace App\Services;

use App\Models\FavoriteStock;

class FavoriteStocksService
{

	public function getUserFavorites(int $userId) {
		return FavoriteStock::where([
			'user_id' => $userId
		])->get();
	}

	public function create(int $userId, string $stockSymbol) {
		return FavoriteStock::create([
			'user_id' => $userId,
			'stock_symbol' => $stockSymbol
		]);
	}

	public function delete(int $favoriteStockId, int $userId) {
		$favoriteStock = FavoriteStock::where([
			'id' => $favoriteStockId,
			'user_id' => $userId
		])->firstOrFail();

		return $favoriteStock->delete();
	}
}