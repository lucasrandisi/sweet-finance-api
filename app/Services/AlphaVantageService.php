<?php


namespace App\Services;


use App\Models\AlphaVantageKey;
use Illuminate\Support\Facades\Http;

class AlphaVantageService
{
    private string $apiKey;
    private string $baseUrl = 'https://www.alphavantage.co/query';

    public function __construct() {
        $this->apiKey = AlphaVantageKey::all()->random(1)->first()->api_key;
    }

    public function query(array $parameters = []) {
        $parameters['apikey'] = $this->apiKey;

        return Http::get($this->baseUrl, $parameters)->json();
    }
}
