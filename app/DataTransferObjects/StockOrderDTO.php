<?php

namespace App\DataTransferObjects;

use App\Http\Requests\StoreStockOrderRequest;

class StockOrderDTO extends DataTransferObject
{
	public string $action;
	public int $amount;
	public ?float $stop = null;
	public float $limit;
	public string $stock_symbol;

	public static function fromRequest(StoreStockOrderRequest $request){
		return new self($request->validated());
	}
}