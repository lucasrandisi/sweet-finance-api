<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use App\Services\AlphaVantageService;
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
                'function' => 'OVERVIEW',
                'symbol' => $value
            ];

            $alphaVantageService = new AlphaVantageService();

            $response = $alphaVantageService->query($parameters);

            if (!$response) {
                throw new NotFoundException('Symbol not found', 101);
            }

            $stock = Stock::create([
                'symbol' => $response['Symbol'],
                'name' => $response['Name']
            ]);

            return $stock;
        });
    }
}
