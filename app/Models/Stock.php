<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use App\Services\AlphaVantageService;
use App\Services\TwelveDataService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $primaryKey = 'symbol';
	protected $keyType = 'string';
	public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'symbol',
        'name'
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return Stock::where('symbol', $value)->firstOr(function() use ($value) {
            $parameters = [
                'symbol' => $value
            ];

            $twelveDataService = new TwelveDataService();

            $response = $twelveDataService->getStocks($parameters);

            if (!$response) {
                throw new NotFoundException('Symbol not found', 101);
            }

            // Stocks records are repeated for each country exchange
            $retrievedStock = $response[0];

            $stock = Stock::create([
                'symbol' => $retrievedStock['symbol'],
                'name' => $retrievedStock['name']
            ]);

            return $stock;
        });
    }
}
