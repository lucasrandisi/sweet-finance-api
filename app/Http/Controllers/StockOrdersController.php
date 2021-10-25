<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\StockOrderDTO;
use App\Http\Requests\CreateStockOrderRequest;
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

        $orders = $this->stockOrdersService->getUserOrders($currentUser);

		return response()->json($orders);
    }


    public function store(CreateStockOrderRequest $request)
    {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$stockOrderDTO = StockOrderDTO::fromRequest($request);

		$stockOrder = $this->stockOrdersService->store($currentUser, $stockOrderDTO);

		return $stockOrder;
    }


    public function destroy(int $id)
    {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		return $this->stockOrdersService->deleteOrder($id, $currentUser->id);
    }
}
