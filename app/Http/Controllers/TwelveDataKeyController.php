<?php

namespace App\Http\Controllers;

use App\Clients\TwelveDataClient;
use Illuminate\Http\Request;

class TwelveDataKeyController extends Controller
{
	private TwelveDataClient $twelveDataClient;

	public function __construct(TwelveDataClient $twelveDataClient) {
		$this->twelveDataClient = $twelveDataClient;
	}

	public function __invoke(Request $request, string $path) {
		$queryString = $request->query();

		$response = $this->twelveDataClient->getData($path, $queryString);

		return response()->json($response->json());
	}
}
