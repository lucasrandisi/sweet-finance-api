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

	public function __invoke(Request $request, string $path) {
		$queryString = $request->query();

		$result = $this->twelveDataService->getData($path, $queryString);

		return response()->json($result);
	}
}
