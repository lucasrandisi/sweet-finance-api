<?php


namespace App\Services;


use App\Models\AlphaVantageKey;
use Illuminate\Support\Facades\Http;

class AlphaVantageService
{
	const BASE_URL = 'https://www.alphavantage.co/query';
	private string $apiKey;

    public function __construct() {
        $this->apiKey = AlphaVantageKey::all()->random(1)->first()->api_key;
    }

    public function query(array $parameters = []) {
        $parameters['apikey'] = $this->apiKey;

        return Http::get(AlphaVantageService::BASE_URL, $parameters)->json();
    }
}
