<?php

namespace App\Services;

use App\Models\TwelveDataKey;
use Illuminate\Support\Facades\Http;

class TwelveDataService
{
	const BASE_URL = 'https://api.twelvedata.com';
	private string $apiKey;

	/* Api keys that have reached the limit amount */
	private array $expiredApiKeys = [];

	public function __construct() {
		$this->setApiKey();
	}

	public function setApiKey() {
		$this->apiKey = TwelveDataKey::all()
			->whereNotIn('api_key', $this->expiredApiKeys)
			->random(1)
			->first()
			->api_key;
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

		$response = Http::get($url, array_merge($parameters, $baseParameters));

		// Retry http call with another api key
		if ($response->ok() && $response->json('code') === 429) {
			array_push($this->expiredApiKeys, $this->apiKey);
			$this->setApiKey();

			return $this->getData($path, $parameters);
		}

		return $response;
	}
}