<?php

namespace App\Services;

use App\Models\AlphaVantageKey;
use App\Models\TwelveDataKey;
use Illuminate\Support\Facades\Http;

class TwelveDataService
{
	const BASE_URL = 'https://api.twelvedata.com';
	private string $apiKey;

	public function __construct() {
		$this->apiKey = TwelveDataKey::all()->random(1)->first()->api_key;
	}

	/**
	 * @return string[]
	 */
	private function constructBaseParameters() {
		return ['apikey' => $this->apiKey];
	}

	private function constructUrl(string $resourcePath) {
		return TwelveDataService::BASE_URL . '/' . $resourcePath;
	}

	public function getData(string $path, array $parameters = []) {
		$url = $this->constructUrl($path);
		$baseParameters = $this->constructBaseParameters();

		return Http::get($url, array_merge($parameters, $baseParameters))->json();
	}
}