<?php

namespace App\Http\Controllers;

use App\Clients\FmpClient;
use App\Http\Requests\ThirdClientRequest;

class FmpController extends Controller
{
	private FmpClient $fmpClient;

	public function __construct(FmpClient $fmpClient) {
		$this->fmpClient = $fmpClient;
	}

	public function __invoke(ThirdClientRequest $request) {
		$path = $request->get('path');
		$queryString = $request->except('path');

		$response = $this->fmpClient->getData($path, $queryString);

		return response()->json($response->json());
	}
}