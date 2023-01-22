<?php

namespace App\Http\Controllers;

use App\Clients\MarketauxClient;
use App\Http\Requests\ThirdClientRequest;

class MarketauxController extends Controller
{
	private MarketauxClient $marketauxClient;

	public function __construct(MarketauxClient $marketauxClient) {
		$this->marketauxClient = $marketauxClient;
	}

	public function __invoke(ThirdClientRequest $request) {
		$path = $request->get('path');
		$queryString = $request->except('path');

		$response = $this->marketauxClient->getData($path, $queryString);

		return response()->json($response->json());
	}
}