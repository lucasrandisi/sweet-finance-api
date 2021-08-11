<?php

namespace App\Models;

use App\Services\AlphaVantageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'symbol',
        'name'
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return Stock::where('symbol', $value)->firstOr(function() use ($value) {
            $parameters = [
                'function' => 'OVERVIEW',
                'symbol' => $value
            ];

            $alphaVantageService = new AlphaVantageService();

            $response = $alphaVantageService->query($parameters);

            $stock = Stock::create([
                'symbol' => $response['Symbol'],
                'name' => $response['Name']
            ]);

            return $stock;
        });
    }
}
