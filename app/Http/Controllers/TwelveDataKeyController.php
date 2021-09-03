<?php

namespace App\Http\Controllers;

use App\Services\TwelveDataService;
use Illuminate\Http\Request;

class TwelveDataKeyController extends Controller
{
	private TwelveDataService $twelveDataService;

	public function __construct(TwelveDataService $twelveDataService) {
		$this->twelveDataService = $twelveDataService;
	}

	public function stocks(Request $request) {
		$queryString = $request->query();

		$result = $this->twelveDataService->getStocks($queryString);

		return response()->json($result);
	}

	public function price(Request $request) {
		$queryString = $request->query();

		$result = $this->twelveDataService->getPrice($queryString);

		return response()->json($result);
	}

	public function quote(Request $request) {
		$queryString = $request->query();

		$result = $this->twelveDataService->getQuote($queryString);

		return response()->json($result);
	}
}
