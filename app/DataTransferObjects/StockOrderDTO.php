<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class StockOrderDTO extends DataTransferObject
{
	public string $action;
	public int $amount;
	public ?float $stop = null;
	public float $limit;
	public string $stock_symbol;

	public static function fromRequest(Request $request){
		return new self($request->all());
	}
}