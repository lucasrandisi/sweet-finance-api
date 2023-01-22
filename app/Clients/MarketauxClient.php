<?php

namespace App\Clients;

use App\Models\MarketauxApiKey;
use Illuminate\Support\Facades\Http;

class MarketauxClient
{
	const BASE_URL = 'https://api.marketaux.com/v1';
	private string $apiKey;

	/* Api keys that have reached the limit amount */
	private array $expiredApiKeys = [];

	public function __construct() {
		$this->setApiKey();
	}

	public function setApiKey() {
		$this->apiKey = MarketauxApiKey::all()
			->whereNotIn('api_key', $this->expiredApiKeys)
			->random(1)
			->first()
			->api_key;
	}

	/**
	 * @return string[]
	 */
	private function constructBaseParameters() {
		return ['api_token' => $this->apiKey];
	}

	private function constructUrl(string $resourcePath) {
		return self::BASE_URL . '/' . $resourcePath;
	}

	public function getData(string $path, array $parameters = []) {
		$url = $this->constructUrl($path);
		$baseParameters = $this->constructBaseParameters();

		$response = Http::get($url, array_merge($parameters, $baseParameters));

		// Retry http call with another api key
		if ($response->ok() && in_array($response->json('code') , [402, 429])) {
			array_push($this->expiredApiKeys, $this->apiKey);
			$this->setApiKey();

			return $this->getData($path, $parameters);
		}

		return $response;
	}
}