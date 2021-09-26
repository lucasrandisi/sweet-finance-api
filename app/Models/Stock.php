<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use App\Services\StocksService;
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
        $stocksService = new StocksService(new TwelveDataService());

		$stock = $stocksService->getOne($value);

		if (!$stock) {
			throw new NotFoundException('Symbol not found', 101);
		}

		return $stock;
    }
}
