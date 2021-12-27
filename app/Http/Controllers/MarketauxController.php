<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarketauxRequest;
use App\Services\MarketauxService;
use Illuminate\Http\Request;

class MarketauxController extends Controller
{
	private MarketauxService $marketauxService;

	public function __construct(MarketauxService $marketauxService) {
		$this->marketauxService = $marketauxService;
	}

	public function __invoke(MarketauxRequest $request) {
		$path = $request->get('path');
		$queryString = $request->except('path');

		$response = $this->marketauxService->getData($path, $queryString);

		return response()->json($response->json());
	}
}