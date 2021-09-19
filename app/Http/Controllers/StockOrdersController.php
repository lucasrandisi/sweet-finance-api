<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\StockOrderDTO;
use App\Http\Requests\StoreStockOrder;
use App\Models\Stock;
use App\Models\User;
use App\Services\StockOrdersService;
use Illuminate\Support\Facades\Auth;

class StockOrdersController extends Controller
{
	private StockOrdersService $stockOrdersService;

	public function __construct(StockOrdersService $stockOrdersService) {
		$this->stockOrdersService = $stockOrdersService;
	}


	public function index()
    {
        //
    }


    public function store(Stock $stock, StoreStockOrder $request)
    {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$stockOrderDTO = StockOrderDTO::fromRequest($request);

		$stockOrder = $this->stockOrdersService->store($stock, $currentUser, $stockOrderDTO);

		return $stockOrder;
    }


    public function destroy($id)
    {
        //
    }
}
