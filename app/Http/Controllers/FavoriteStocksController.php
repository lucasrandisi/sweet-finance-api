<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFavoriteStockRequest;
use App\Models\User;
use App\Services\FavoriteStocksService;
use Illuminate\Support\Facades\Auth;

class FavoriteStocksController extends Controller
{
	private FavoriteStocksService $favoriteStocksService;

	public function __construct(
		FavoriteStocksService $favoriteStocksService
	) {
		$this->favoriteStocksService = $favoriteStocksService;
	}

	public function index() {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$favoriteStocks = $this->favoriteStocksService->getUserFavorites($currentUser->id);

		return $favoriteStocks;
	}

	public function store(CreateFavoriteStockRequest $request) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$stockSymbol = $request->stock_symbol;

		$favoriteStock = $this->favoriteStocksService->create($currentUser->id, $stockSymbol);

		return $favoriteStock;
	}

	public function destroy(int $favoriteStockid) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		return $this->favoriteStocksService->delete($favoriteStockid, $currentUser->id);
	}
}
