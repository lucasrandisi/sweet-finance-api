<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\StockOrderDTO;
use App\Http\Requests\StoreStockOrderRequest;
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
		/*  @var User $currentUser */
		$currentUser = Auth::user();

        return $this->stockOrdersService->getUserOrders($currentUser);
    }


    public function store(StoreStockOrderRequest $request)
    {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$stockOrderDTO = StockOrderDTO::fromRequest($request);

		$stockOrder = $this->stockOrdersService->store($currentUser, $stockOrderDTO);

		return $stockOrder;
    }


    public function destroy(int $id)
    {
		return $this->stockOrdersService->deleteOrder($id);
    }
}
