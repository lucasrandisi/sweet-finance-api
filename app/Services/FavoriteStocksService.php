<?php

namespace App\Services;

use App\Exceptions\UnprocessableEntityException;
use App\Models\FavoriteStock;
use Illuminate\Database\QueryException;

class FavoriteStocksService
{

	public function getUserFavorites(int $userId) {
		return FavoriteStock::where([
			'user_id' => $userId
		])->get();
	}

	public function create(int $userId, string $stockSymbol) {
		try {
			return FavoriteStock::create([
				'user_id' => $userId,
				'stock_symbol' => $stockSymbol
			]);
		}
		catch (QueryException $exception) {
			throw new UnprocessableEntityException('El ticker ya se encuentra agregado a favoritos', 300);
		}
	}

	public function delete(int $favoriteStockId, int $userId) {
		$favoriteStock = FavoriteStock::where([
			'id' => $favoriteStockId,
			'user_id' => $userId
		])->firstOrFail();

		return $favoriteStock->delete();
	}
}